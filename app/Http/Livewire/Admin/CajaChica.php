<?php

namespace App\Http\Livewire\Admin;

use App\Models\Cajachica as ModelsCajachica;
use App\Models\Cliente;
use App\Models\Empleado;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CajaChica extends Component
{
    public $gestion = '', $empleado_id = '', $cliente_id = '', $filas = 5, $modalMode = 'Nuevo', $procesando = false;
    public $selEmpleado = '', $selGestion = '', $selInicio = '', $selFinal = '', $selObservaciones = '', $selEstado = 'ACTIVA', $selCaja;
    public $montoDeposito;
    public $conceptoDeposito;

    public $filtroGestion;
    public $filtroMes;

    public $movimientos = [];
    public function mount()
    {
        $this->gestion = date('Y');
        $this->selGestion = date('Y');
    }
    public function render()
    {
        $anioActual = Carbon::now()->year;

        $aniosBD = ModelsCajachica::select('gestion')
            ->distinct()
            ->pluck('gestion')
            ->toArray();

        $aniosExtra = [$anioActual, $anioActual + 1];

        $gestiones = collect(array_merge($aniosBD, $aniosExtra))
            ->unique()
            ->sortDesc()
            ->values();



        $cajachicas = ModelsCajachica::query()
            ->when($this->gestion, function ($query) {
                $query->where('gestion', $this->gestion);
            })
            ->when($this->empleado_id, function ($query) {
                $query->where('empleado_id', $this->empleado_id);
            })
            ->paginate($this->filas);
       $supervisores = Empleado::whereHas('area', function ($query) {
            $query->where('template', 'SUPERVISOR');
        })->get();


        return view('livewire.admin.caja-chica', compact('cajachicas', 'gestiones', 'supervisores'))->extends('adminlte::page');
    }

    protected $listeners = ['store', 'update', 'registrarDeposito'];

    public function store()
    {

        $this->validate([
            'selEmpleado' => 'required',
            'selGestion' => 'required',
            'selInicio' => 'required',
        ]);

        if ($this->procesando) {
            return;
        }
        $this->procesando = true;
        DB::beginTransaction();
        try {
            $cajachica = ModelsCajachica::create([
                'empleado_id' => $this->selEmpleado,
                'gestion' => $this->selGestion,
                'fecha_apertura' => $this->selInicio,
                'observacion' => $this->selObservaciones,
            ]);

            DB::commit();
            $this->cerrarModal();
            $this->procesando = false;

            $this->emit('success', 'Caja Chica registrada correctamente.');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->procesando = false;
            $this->emit('error', 'Ha ocurrido un error.');
        }
    }

    public function create()
    {
        $this->modalMode = 'Nuevo';
        $this->emit('openModal');
    }

    public function show($cajachica_id)
    {
        $this->selCaja = ModelsCajachica::find($cajachica_id);
        $this->selEmpleado = $this->selCaja->empleado_id;
        $this->selGestion = $this->selCaja->gestion;
        $this->selInicio = $this->selCaja->fecha_apertura;
        $this->selFinal = $this->selCaja->fecha_cierre;
        $this->selObservaciones = $this->selCaja->observacion;
        $this->selEstado = $this->selCaja->estado;

        $this->modalMode = 'Ver';
        $this->emit('openModal');
    }

    public function edit($cajachica_id)
    {
        $this->selCaja = ModelsCajachica::find($cajachica_id);
        $this->selEmpleado = $this->selCaja->empleado_id;
        $this->selGestion = $this->selCaja->gestion;
        $this->selInicio = $this->selCaja->fecha_apertura;
        $this->selFinal = $this->selCaja->fecha_cierre;
        $this->selObservaciones = $this->selCaja->observacion;
        $this->selEstado = $this->selCaja->estado;

        $this->modalMode = 'Editar';
        $this->emit('openModal');
    }

    public function update()
    {
        $this->validate([
            'selEmpleado' => 'required',
            'selGestion' => 'required',
            'selInicio' => 'required',
        ]);

        if ($this->procesando) {
            return;
        }

        if ($this->selEstado == 'CERRADA') {
            if ($this->selCaja->saldo_actual > 0) {
                $this->emit('error', 'Existe un monto de saldo, debe estar en 0 para cerrar la caja.');
                return;
            }
        }

        $this->procesando = true;
        DB::beginTransaction();
        try {
            $this->selCaja->update([
                'empleado_id' => $this->selEmpleado,
                'gestion' => $this->selGestion,
                'fecha_apertura' => $this->selInicio,
                'fecha_cierre' => $this->selFinal ? $this->selFinal : NULL,
                'estado' => $this->selEstado,
                'observacion' => $this->selObservaciones,
            ]);

            DB::commit();
            $this->cerrarModal();
            $this->procesando = false;

            $this->emit('success', 'Caja Chica actualizada correctamente.');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->procesando = false;
            $this->emit('error', 'Ha ocurrido un error.');
        }
    }

    public function cerrarModal()
    {
        $this->emit('closeModal');
        $this->reset('modalMode', 'selEmpleado', 'selInicio', 'selFinal', 'selObservaciones', 'selEstado');
        $this->selGestion = date('Y');

    }

    public function depositar($cajachica_id)
    {
        $this->selCaja = ModelsCajachica::find($cajachica_id);

        $this->emit('openDepositos');
    }

    public function cancelarDeposito()
    {
        $this->reset(['montoDeposito', 'conceptoDeposito', 'selCaja']);
        $this->emit('closeDepositos');
    }

    public function registrarDeposito()
    {
        if ($this->selCaja->estado == 'CERRADA') {
            $this->emit('error', 'La Caja se encuentra cerrada.');
            return;
        }

        $this->validate([
            'montoDeposito' => 'required|numeric|min:0.01',
            'conceptoDeposito' => 'required|string|min:3',
        ]);
        if ($this->procesando) {
            return;
        }
        $this->procesando = true;
        DB::beginTransaction();
        try {
            $this->selCaja->movimientocajas()->create([
                'fecha' => now()->toDateString(),
                'tipo' => 'INGRESO',
                'monto' => $this->montoDeposito,
                'concepto' => $this->conceptoDeposito,
            ]);
            DB::commit();
            // Reset campos
            $this->reset(['montoDeposito', 'conceptoDeposito', 'procesando']);

            // Refrescar la caja
            $this->selCaja->refresh();

            // Cerrar modal
            $this->emit('closeDepositos');

            // Mensaje opcional
            $this->emit('success', 'Depósito registrado correctamente.');

        } catch (\Throwable $th) {
            DB::rollBack();
            $this->emit('error', 'Ha ocurrido un error.');
            $this->procesando = false;
        }

    }

    public $totalIngresos = 0;
    public $totalEgresos = 0;
    public $saldoPeriodo = 0;

    public function verMovimientos($cajaId)
    {
        $this->selCaja = ModelsCajachica::findOrFail($cajaId);

        $this->filtroGestion = $this->selCaja->gestion;
        $this->filtroMes = null;

        $this->cargarMovimientos();

        $this->emit('abrirModalMovimientos');
    }

    public function cerrarMovimientos()
    {
        $this->emit('cerrarModalMovimientos');
        $this->reset(['filtroGestion', 'filtroMes']);
    }

    public function cargarMovimientos()
    {
        $query = $this->selCaja->movimientocajas()
            ->whereYear('fecha', $this->filtroGestion);

        if ($this->filtroMes) {
            $query->whereMonth('fecha', $this->filtroMes);
        }

        $this->movimientos = $query->orderBy('fecha')->get();

        $this->totalIngresos = $query->where('tipo', 'INGRESO')->sum('monto');
        $this->totalEgresos = $query->where('tipo', 'EGRESO')->sum('monto');
        $this->saldoPeriodo = $this->totalIngresos - $this->totalEgresos;
    }



    public function updatedFiltroGestion()
    {
        $this->filtroMes = null;
        $this->cargarMovimientos();
    }

    public function updatedFiltroMes()
    {
        $this->cargarMovimientos();
    }

}
