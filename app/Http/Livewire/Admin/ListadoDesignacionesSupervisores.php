<?php

namespace App\Http\Livewire\Admin;

use App\Models\Cliente;
use App\Models\Designaciondia;
use App\Models\Designacione;
use App\Models\Designacioneturno;
use App\Models\Designacionsupervisor;
use App\Models\Designacionsupervisorcliente;
use App\Models\Empleado;
use App\Models\Turnoguardia;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ListadoDesignacionesSupervisores extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $busqueda = "", $filas = 10;
    public $fechaInicio, $fechaFin, $supervisor_id, $observaciones = '', $cliente_id = "", $arrayClientes = [], $procesando = false, $estado;
    public $designacion, $editMode = false;
    public $turnoguardia_id = NULL, $intervalo_hv = 0;
    public $lunes = false, $martes = false, $miercoles = false, $jueves = false, $viernes = false, $sabado = false, $domingo = false;

    public function mount()
    {
        // $this->fechaInicio = date('Y-m-d');
    }

    public function render()
    {
        $supervisores = Empleado::whereHas('area', function ($query) {
            $query->where('template', 'SUPERVISOR');
        })
            ->whereHas('user', function ($query) {
                $query->where('status', 1);
            })
            ->get();
        $clientes = Cliente::where('status', 1)->get();
        $resultados = Designacionsupervisor::paginate($this->filas);
        $turnoguardias = Turnoguardia::all();
        return view('livewire.admin.listado-designaciones-supervisores', compact('resultados', 'supervisores', 'clientes', 'turnoguardias'))->extends('adminlte::page');
    }

    protected $listeners = ['render', 'eliminar'];

    public function  create()
    {
        $this->emit('openModalCreate');
    }

    public function updatedBusqueda()
    {
        $this->resetPage();
    }

    public function updatedFilas()
    {
        $this->resetPage();
    }

    public function agregarCliente()
    {
        $this->validate([
            'cliente_id' => 'required',
        ]);
        $cliente = Cliente::find($this->cliente_id);
        if (!$cliente) {
            $this->emit('alert', 'error', 'El cliente seleccionado no es válido');
            return;
        }
        $this->arrayClientes[] = array(
            'id' => $cliente->id,
            'nombre' => $cliente->nombre,
        );
        $this->reset('cliente_id');
    }

    public function quitarCliente($i)
    {
        unset($this->arrayClientes[$i]);
        $this->arrayClientes = array_values($this->arrayClientes);
    }

    public function resetForm()
    {
        $this->reset(['supervisor_id', 'cliente_id', 'arrayClientes', 'fechaInicio', 'observaciones', 'editMode']);
        $this->reset(['turnoguardia_id', 'intervalo_hv', 'fechaFin', 'lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo']);
    }

    public function registrar()
    {
        if ($this->procesando) {
            return;
        }

        $this->validate([
            'supervisor_id' => 'required',
            'arrayClientes' => 'required|array|min:1',
            'fechaInicio' => 'required|date',
            'fechaFin' => 'required|date|after:fechaInicio',
        ]);
        $this->procesando = true;
        DB::beginTransaction();
        try {
            $designacion = Designacionsupervisor::create([
                'empleado_id' => $this->supervisor_id,
                'turnoguardia_id' => $this->turnoguardia_id,
                'fechaInicio' => $this->fechaInicio,
                'fechaFin' => $this->fechaFin,
                'observaciones' => $this->observaciones,
            ]);

            foreach ($this->arrayClientes as $cliente) {
                Designacionsupervisorcliente::create([
                    'designacionsupervisor_id' => $designacion->id,
                    'cliente_id' => $cliente['id'],
                ]);
            }


            $designacionGral = Designacione::create([
                "empleado_id" => $this->supervisor_id,
                "tipo_designacion" => "SUPERVISOR",
                "turno_id" => NULL,
                "fechaInicio" => $this->fechaInicio,
                "fechaFin" => $this->fechaFin,
                "intervalo_hv" => $this->intervalo_hv,
                "observaciones" => $this->observaciones,
            ]);

            $designacionturno = Designacioneturno::create([
                "designacione_id" => $designacionGral->id,
                "turnoguardia_id" => $this->turnoguardia_id,
            ]);

            $dias = Designaciondia::create([
                "designacione_id" => $designacionGral->id,
                "lunes" => $this->lunes,
                "martes" => $this->martes,
                "miercoles" => $this->miercoles,
                "jueves" => $this->jueves,
                "viernes" => $this->viernes,
                "sabado" => $this->sabado,
                "domingo" => $this->domingo,
            ]);
            DB::commit();
            $this->procesando = false;
            $this->resetForm();
            $this->emit('success', 'Designación registrada correctamente');
            $this->emit('closeModalCreate');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->procesando = false;
            $this->emit('error', $th->getMessage());
            // $this->emit('error', 'Ocurrió un error al registrar la designación');
        }
    }

    public function update()
    {
        if ($this->procesando) {
            return;
        }

        $this->validate([
            'supervisor_id' => 'required',
            'turnoguardia_id' => 'required',
            'arrayClientes' => 'required|array|min:1',
            'fechaInicio' => 'required|date',
            'fechaFin' => 'required|date|after:fechaInicio',
        ]);
        $this->procesando = true;
        DB::beginTransaction();
        try {
        $this->designacion->update([
            'empleado_id' => $this->supervisor_id,
            'turnoguardia_id' => $this->turnoguardia_id,
            'fechaInicio' => $this->fechaInicio,
            'fechaFin' => $this->fechaFin,
            'observaciones' => $this->observaciones,
            'estado' => $this->estado,
        ]);
        $this->designacion->save();

        $this->designacion->designacionsupervisorclientes()->delete();

        foreach ($this->arrayClientes as $cliente) {
            Designacionsupervisorcliente::create([
                'designacionsupervisor_id' => $this->designacion->id,
                'cliente_id' => $cliente['id'],
            ]);
        }

        if (!$this->designacion->estado) {
            if (is_null($this->designacion->fechaFin)) {
                $this->designacion->fechaFin = date('Y-m-d');
                $this->designacion->save();
            }
        }

        $designacionGral = Designacione::where('empleado_id', $this->designacion->empleado_id)
            ->where('tipo_designacion', 'SUPERVISOR')
            ->where('fechaInicio', $this->designacion->fechaInicio)
            ->first();
        $designacionDia = $designacionGral->designacionturno;
        if ($designacionDia) {
            $designacionDia->delete();
        }


        $designacionturno = Designacioneturno::create([
            "designacione_id" => $designacionGral->id,
            "turnoguardia_id" => $this->turnoguardia_id,
        ]);

        $designacionGral->update([
            "fechaInicio" => $this->fechaInicio,
            "fechaFin" => $this->fechaFin,
            "observaciones" => $this->observaciones,
        ]);

        $designacionGral->designaciondias()->delete();
        $dias = Designaciondia::create([
            "designacione_id" => $designacionGral->id,
            "lunes" => $this->lunes,
            "martes" => $this->martes,
            "miercoles" => $this->miercoles,
            "jueves" => $this->jueves,
            "viernes" => $this->viernes,
            "sabado" => $this->sabado,
            "domingo" => $this->domingo,
        ]);

        DB::commit();
        $this->procesando = false;
        $this->resetForm();
        $this->emit('success', 'Designación actualizada correctamente');
        $this->emit('closeModalCreate');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->procesando = false;
            // $this->emit('error', 'Ocurrió un error al actualizar la designación');
            $this->emit('error', $th->getMessage());
        }
    }

    public function editar($id)
    {

        $this->designacion = Designacionsupervisor::find($id);

        if ($this->designacion) {
            $this->resetForm();
            $this->supervisor_id = $this->designacion->empleado_id;
            $this->fechaInicio = $this->designacion->fechaInicio;
            $this->fechaFin = $this->designacion->fechaFin;
            $this->observaciones = $this->designacion->observaciones;
            $this->estado = $this->designacion->estado;
            $this->turnoguardia_id = $this->designacion->turnoguardia_id;
            foreach ($this->designacion->designacionsupervisorclientes as $item) {
                $this->arrayClientes[] = array(
                    'id' => $item->cliente->id,
                    'nombre' => $item->cliente->nombre,
                );
            }
        }
        $designacionturno = Designacioneturno::where('designacione_id', $this->designacion->id)->first();
        if ($designacionturno) {
            $this->turnoguardia_id = $designacionturno->turnoguardia_id;
        }

        $designacionGral = Designacione::where('empleado_id', $this->designacion->empleado_id)
            ->where('tipo_designacion', 'SUPERVISOR')
            ->where('fechaInicio', $this->designacion->fechaInicio)
            ->first();


        $dias = $designacionGral->designaciondias;
        if ($dias) {
            $this->lunes = $dias->lunes;
            $this->martes = $dias->martes;
            $this->miercoles = $dias->miercoles;
            $this->jueves = $dias->jueves;
            $this->viernes = $dias->viernes;
            $this->sabado = $dias->sabado;
            $this->domingo = $dias->domingo;
        }


        $this->editMode = true;
        $this->emit('openModalCreate',);
    }
    public function seleccionarTodosDias()
    {
        $this->lunes = true;
        $this->martes = true;
        $this->miercoles = true;
        $this->jueves = true;
        $this->viernes = true;
        $this->sabado = true;
        $this->domingo = true;
    }

    public function eliminar($id)
    {
        $designacion = Designacionsupervisor::find($id);
        if ($designacion) {
            $designacionGral = Designacione::where('empleado_id', $designacion->empleado_id)
                ->where('tipo_designacion', 'SUPERVISOR')
                ->where('fechaInicio', $designacion->fechaInicio)
                ->first();
            if ($designacionGral) {
                $designacionGral->delete();
            }
            $designacion->delete();
            $this->emit('success', 'Designación eliminada correctamente');
        } else {
            $this->emit('error', 'No se encontró la designación');
        }
    }
}
