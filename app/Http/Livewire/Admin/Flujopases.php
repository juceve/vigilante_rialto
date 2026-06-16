<?php

namespace App\Http\Livewire\Admin;

use App\Models\Cliente;
use App\Models\Flujopase;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Flujopases extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '', $inicio, $final, $flujo = '';
    public $cliente_id = '';
    public $perPage = 10; // nueva propiedad para controlar filas por página

    // Nueva propiedad para la vista detallada
    public $visita = [];
    public $imgs = [];

    public function mount()
    {
        $this->inicio = date('Y-m-d');
        $this->final = date('Y-m-d');
    }

    // Reiniciar paginación cuando cambien filtros o perPage
    public function updatedPerPage()
    {
        $this->resetPage();
    }
    public function updatedClienteId()
    {
        $this->resetPage();
    }
    public function updatedInicio()
    {
        $this->resetPage();
    }
    public function updatedFinal()
    {
        $this->resetPage();
    }

    public function render()
    {
        $clientes = Cliente::where('status', 1)
            ->orderBy('nombre')
            ->pluck('nombre', 'id');

        // filas por página
        $perPage = ($this->perPage === 'all') ? PHP_INT_MAX : (int)$this->perPage;

        // 1) Obtener flujopases de tipo INGRESO dentro del rango y (opcional) por cliente
        $ingQuery = Flujopase::from('flujopases as f')
            ->join('paseingresos as pi', 'pi.id', '=', 'f.paseingreso_id')
            ->leftJoin('residencias as re', 're.id', '=', 'pi.residencia_id')
            ->leftJoin('propietarios as pro', 'pro.id', '=', 're.propietario_id')
            ->leftJoin('clientes as cl', 'cl.id', '=', 're.cliente_id')
            ->whereRaw("UPPER(f.tipo) = 'INGRESO'")
            ->whereBetween('f.fecha', [$this->inicio, $this->final])
            ->select(
                'f.id as flujopase_id',
                'f.paseingreso_id',
                'f.fecha as fecha_ingreso_raw',
                'f.hora as hora_ingreso_raw',
                'f.anotaciones as anotacion_ingreso_raw',
                'pi.nombre as visitante',
                'pi.cedula',
                'cl.nombre as cliente_nombre',
                'pro.nombre as propietario_nombre',
                're.calle',
                're.numeropuerta',
                're.manzano'
            );

        if (!empty($this->cliente_id)) {
            $ingQuery->where('re.cliente_id', $this->cliente_id);
        }

        $ingPaginator = $ingQuery->orderBy('f.fecha', 'desc')->orderBy('f.hora', 'desc')->paginate($perPage);

        $ingIds = $ingPaginator->pluck('flujopase_id')->toArray();

        $rowsCollection = collect();

        if (!empty($ingIds)) {
            // Cargar los ingresos de la página actual (ya vienen en paginator); iterar y buscar salida correspondiente
            $ingresos = Flujopase::whereIn('id', $ingIds)
                ->orderBy('fecha', 'desc')
                ->orderBy('hora', 'desc')
                ->get()
                ->keyBy('id');

            foreach ($ingPaginator->items() as $ingRow) {
                $flujopase_id = $ingRow->flujopase_id;
                $ingModel = $ingresos->get($flujopase_id);

                if (!$ingModel) continue;

                // buscar salida correspondiente: primer SALIDA del mismo paseingreso con datetime >= ingreso
                $ingDtStr = ($ingModel->fecha ?? '') . ' ' . ($ingModel->hora ?? '00:00:00');

                $salModel = Flujopase::where('paseingreso_id', $ingModel->paseingreso_id)
                    ->whereRaw("UPPER(tipo) = 'SALIDA'")
                    ->whereRaw("CONCAT(fecha, ' ', COALESCE(hora,'00:00:00')) >= ?", [$ingDtStr])
                    ->orderBy('fecha', 'asc')
                    ->orderBy('hora', 'asc')
                    ->first();

                // obtener datos de residente/cliente/visitante desde la fila del join (ingRow) o fallback desde relaciones
                $propietarioNombre = $ingRow->propietario_nombre ?? null;
                $direccion = trim(
                    ($ingRow->calle ?? '') . ' ' .
                    ($ingRow->numeropuerta ? 'N° ' . $ingRow->numeropuerta : '') . ' ' .
                    ($ingRow->manzano ? 'Mza ' . $ingRow->manzano : '')
                );
                $residente = $propietarioNombre ? $propietarioNombre : ($direccion ?: null);

                $rowsCollection->push((object)[
                    'flujopase_id' => $flujopase_id,
                    'paseingreso_id' => $ingModel->paseingreso_id,
                    'visitante' => $ingRow->visitante ?? ($ingModel->nombre ?? null),
                    'cedula' => $ingRow->cedula ?? ($ingModel->cedula ?? null),
                    'cliente' => $ingRow->cliente_nombre ?? null,
                    'residente' => $residente,
                    // fecha/hora del flujopase ingreso/salida (usar los del flujopase)
                    'fecha_ingreso' => $ingModel->fecha ? Carbon::parse($ingModel->fecha)->format('Y-m-d') : null,
                    'hora_ingreso' => $ingModel->hora ?? null,
                    'fecha_salida' => $salModel ? $salModel->fecha : null,
                    'hora_salida' => $salModel ? $salModel->hora : null,
                    // anotaciones del flujopase (campo en la tabla)
                    'anotacion_ingreso' => $ingModel->anotaciones ?? ($ingModel->anotacion ?? null),
                    'anotacion_salida' => $salModel ? ($salModel->anotaciones ?? ($salModel->anotacion ?? null)) : null,
                    'estado' => $salModel ? 'Finalizado' : 'En proceso',
                ]);
            }
        }

        // Reemplazar collection interna del paginator para conservar meta y links
        $ingPaginator->setCollection($rowsCollection->values());

        $rows = $ingPaginator;

        return view('livewire.admin.flujopases', compact('clientes', 'rows'))->extends('adminlte::page');
    }

    // verInfo ahora recibe flujopase_id (registro de INGRESO) y construye $visita a partir de ese flujopase + su SALIDA
    public function verInfo($flujopase_id)
    {
        $ing = Flujopase::with('paseingreso')->find($flujopase_id);

        if (!$ing) {
            $this->visita = [];
            $this->dispatchBrowserEvent('show-modal');
            return;
        }

        // buscar salida correspondiente (primer SALIDA posterior al ingreso)
        $ingDtStr = ($ing->fecha ?? '') . ' ' . ($ing->hora ?? '00:00:00');
        $sal = Flujopase::where('paseingreso_id', $ing->paseingreso_id)
            ->whereRaw("UPPER(tipo) = 'SALIDA'")
            ->whereRaw("CONCAT(fecha, ' ', COALESCE(hora,'00:00:00')) >= ?", [$ingDtStr])
            ->orderBy('fecha', 'asc')
            ->orderBy('hora', 'asc')
            ->first();

        // metadatos desde paseingreso -> residencia -> cliente/propietario
        $pi = $ing->paseingreso;
        $residencia = null;
        if (!empty(optional($pi)->residencia_id)) {
            $residencia = optional($pi)->residencia;
        }
        $cliente = $residencia ? optional($residencia)->cliente : null;
        $propietarioModel = $residencia ? optional($residencia)->propietario : null;
        $propietarioNombre = optional($propietarioModel)->nombre ?? null;

        if ($residencia) {
            $calle = optional($residencia)->calle ?? null;
            $nro = optional($residencia)->numeropuerta ?? null;
            $piso = optional($residencia)->piso ?? null;
            $nrolote = optional($residencia)->nrolote ?? null;
            $manzano = optional($residencia)->manzano ?? null;
            $direccion = trim(($calle ? $calle : '') . ' ' . ($nro ? 'N° '.$nro : '') . ' ' . ($manzano ? 'Mza '.$manzano : ''));
            $residenteFinal = $propietarioNombre ? $propietarioNombre : ($direccion ?: null);
        } else {
            $calle = $nro = $piso = $nrolote = $manzano = null;
            $residenteFinal = null;
        }

        // helper anotaciones
        $getAnot = function ($m) {
            if (!$m) return null;
            if (isset($m->anotaciones) && $m->anotaciones !== '') return $m->anotaciones;
            if (isset($m->anotacion) && $m->anotacion !== '') return $m->anotacion;
            return null;
        };

        $this->visita = [
            'flujopase_id' => $ing->id,
            'paseingreso_id' => $ing->paseingreso_id,
            'cliente' => optional($cliente)->nombre ?? null,
            'visitante' => optional($pi)->nombre ?? ($ing->nombre ?? null),
            'docidentidad' => optional($pi)->cedula ?? ($ing->cedula ?? null),
            'residente' => $residenteFinal,
            'residencia_calle' => $calle,
            'residencia_numeropuerta' => $nro,
            'residencia_piso' => $piso,
            'residencia_nrolote' => $nrolote,
            'residencia_manzano' => $manzano,
            // fechas/horas tomadas directamente del registro flujopase
            'fechaingreso' => $ing->fecha ?? null,
            'horaingreso' => $ing->hora ?? null,
            'fechasalida' => $sal->fecha ?? null,
            'horasalida' => $sal->hora ?? null,
            'motivo' => optional(optional($pi)->motivo)->nombre ?? null,
            'observaciones' => optional($pi)->observaciones ?? null,
            'estado' => is_null($sal),
            'empleado' => optional($pi)->empleado ?? null,
            'anotacion_ingreso' => $getAnot($ing),
            'anotacion_ingreso_fecha' => $ing->fecha ?? null,
            'anotacion_ingreso_hora' => $ing->hora ?? null,
            'anotacion_salida' => $getAnot($sal),
            'anotacion_salida_fecha' => $sal->fecha ?? null,
            'anotacion_salida_hora' => $sal->hora ?? null,
        ];

        $this->imgs = [];
        $this->dispatchBrowserEvent('show-modal');
    }

    // Opcional: limpiar y cerrar modal desde Livewire
    public function cerrarModal()
    {
        $this->visita = [];
        $this->imgs = [];
        $this->dispatchBrowserEvent('hide-modal');
    }
}
