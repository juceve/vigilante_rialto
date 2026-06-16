<?php

namespace App\Http\Livewire\Supervisores;

use App\Models\Designacione;
use App\Models\Dialibre;
use App\Models\Inspeccion;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DiasLibres extends Component
{
    public $inspeccionActiva, $designaciones;
    public $mes = '', $gestion;

    public $diaModal = null;
    public $selDesignacione = "", $observaciones = "";
    public $detalleDiaModal = [];

    public function abrirDiaModal($fecha)
    {

        $this->diaModal = $fecha;

        $this->detalleDiaModal = Dialibre::whereDate('fecha', $fecha)
            ->whereHas('designacione.turno', function ($q) {
                $q->where('cliente_id', $this->inspeccionActiva->cliente_id);
            })
            ->with('designacione.empleado')
            ->get();
        $this->emit('openModal');
    }

    public function addDiaLibre()
    {
        $this->validate([
            "diaModal" => "required",
            "selDesignacione" => "required",
        ]);
        DB::beginTransaction();
        try {
            $diaLibre = Dialibre::create([
                "fecha" => $this->diaModal,
                "designacione_id" => $this->selDesignacione,
                "observaciones" => $this->observaciones,
            ]);
            $this->detalleDiaModal = Dialibre::whereDate('fecha', $this->diaModal)
                ->whereHas('designacione.turno', function ($q) {
                    $q->where('cliente_id', $this->inspeccionActiva->cliente_id);
                })
                ->with('designacione.empleado')
                ->get();

            $this->reset('selDesignacione', 'observaciones');

            $this->emit('successToast', "Día Libre registrado con exito!");

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->emit('errorToast', 'Ha ocurrido un error.');
        }
    }

    public function mount($inspeccion_id)
    {
        $this->inspeccionActiva = Inspeccion::find($inspeccion_id);
        $this->mes = (int) date('m');
        ;
        $this->gestion = date('Y');
        $this->designaciones = $this->traeEmpleados($this->inspeccionActiva->cliente_id);
    }

    public function render()
    {
        $anioActual = Carbon::now()->year;

        $aniosBD = DiaLibre::selectRaw('YEAR(fecha) as anio')
            ->distinct()
            ->pluck('anio')
            ->toArray();

        $aniosExtra = [$anioActual, $anioActual + 1];

        $gestiones = collect(array_merge($aniosBD, $aniosExtra))
            ->unique()
            ->sortDesc()
            ->values();

        $inicio = Carbon::create($this->gestion, $this->mes, 1)->startOfMonth();
        $fin = Carbon::create($this->gestion, $this->mes, 1)->endOfMonth();
        $cliente_id = $this->inspeccionActiva->cliente_id;
        $diaslibres = Dialibre::whereBetween('fecha', [$inicio, $fin])
            ->whereHas('designacione.turno', function ($q) use ($cliente_id) {
                $q->where('cliente_id', $cliente_id);
            })
            ->with(['designacione.turno']) // opcional, evita N+1
            ->get();

        // PREPARANDO DATOS
        $inicioMes = Carbon::create($this->gestion, $this->mes, 1)->startOfMonth();
        $finMes = $inicioMes->copy()->endOfMonth();

        // Colección de todos los días del mes
        $diasMes = collect();
        $cursor = $inicioMes->copy();

        while ($cursor <= $finMes) {
            $diasMes->push($cursor->copy());
            $cursor->addDay();
        }

        // Agrupar días libres por fecha
        $diasLibresPorFecha = $diaslibres->groupBy(
            fn($d) =>
            Carbon::parse($d->fecha)->format('Y-m-d')
        );


        return view(
            'livewire.supervisores.dias-libres',
            compact(
                'diaslibres',
                'gestiones',
                'diasMes',
                'diasLibresPorFecha'
            )

        )->extends('layouts.app');

    }

    public function traeEmpleados($cliente_id)
    {
        $hoy = Carbon::today();
        $diaSemanaNum = $hoy->dayOfWeek; // 0 = domingo, 6 = sábado
        $mapDia = [
            0 => 'domingo',
            1 => 'lunes',
            2 => 'martes',
            3 => 'miercoles',
            4 => 'jueves',
            5 => 'viernes',
            6 => 'sabado',
        ];
        $diaColumna = $mapDia[$diaSemanaNum];

        $designaciones = Designacione::with(['empleado', 'designaciondias', 'turno'])
            ->where('estado', true)
            ->whereDate('fechaInicio', '<=', $hoy)
            ->whereDate('fechaFin', '>=', $hoy)
            ->whereHas('designaciondias', function ($q) use ($diaColumna) {
                $q->where($diaColumna, true);
            })
            ->whereHas('turno', function ($q) use ($cliente_id) {
                $q->where('cliente_id', $cliente_id);
            })
            ->whereDoesntHave('dialibres', function ($q) use ($hoy) {
                $q->whereDate('fecha', $hoy);
            })
            ->whereDoesntHave('empleado.rrhhpermisos', function ($q) use ($hoy) {
                $q->where('activo', true)
                    ->whereDate('fecha_inicio', '<=', $hoy)
                    ->whereDate('fecha_fin', '>=', $hoy);
            })
            ->get()
            ->map(function ($designacione) {
                return [
                    'designacione_id' => $designacione->id,
                    'empleado_id' => $designacione->empleado->id,
                    'nombre' => $designacione->empleado->nombres . ' ' . $designacione->empleado->apellidos,
                ];
            });
        return $designaciones;
    }

    // Emitir evento para que JS cambie de mes
    public function updatedMes()
    {
        $this->emit('mesCambiado', $this->mes, $this->gestion);
    }

    public function updatedGestion()
    {
        $this->emit('mesCambiado', $this->mes, $this->gestion);
    }
}
