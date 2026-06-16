<?php

namespace App\Http\Livewire\Admin;

use App\Models\Cliente;
use App\Models\Designaciondia;
use App\Models\Designacione;
use App\Models\Intervalo;
use App\Models\Rrhhbono;
use App\Models\Rrhhtipobono;
use App\Models\Turno;
use App\Models\Vwdesignacione;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;

class Registrosdesignacion extends Component
{
    use WithPagination;

    public $clientes, $cliente_id = "", $selCliente_id = '', $estado = "1", $search = "", $designacioneSelected = null, $turnos = [], $turno_extra_id, $tipobonos = [], $fechaInicio, $rrhhtipobono_id, $intervalo = 1;
    protected $paginationTheme = 'bootstrap'; // Activamos Bootstrap 4 para Livewire

    // Evitar reset de página al cambiar filtros
    protected $updatesQueryString = ['cliente_id', 'estado', 'search'];

    public function mount()
    {
        $this->clientes = Cliente::where('status', 1)->get()->pluck('nombre', 'id');
        $this->tipobonos = Rrhhtipobono::all()->pluck('nombre', 'id');
        $this->fechaInicio = date('Y-m-d');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingClienteId()
    {
        $this->resetPage();
    }

    public function updatingEstado()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Vwdesignacione::query();

        // Filtro por cliente si se selecciona
        if ($this->cliente_id != "") {
            $query->where('cliente_id', $this->cliente_id);
        }

        // Filtro por estado si se selecciona
        if ($this->estado !== "") {
            $query->where('estado', $this->estado);
        }

        // Filtro por búsqueda en empleado o turno
        if ($this->search != "") {
            $query->where(function ($q) {
                $q->where('empleado', 'LIKE', '%' . $this->search . '%')
                    ->orWhere('turno', 'LIKE', '%' . $this->search . '%');
            });
        }


        $resultados = $query->orderBy('id', 'ASC')->paginate(5); // Paginación 10 por página

        // Guardar parámetros en sesión
        $parametros = [$this->cliente_id, $this->estado, $this->search];
        Session::put('param-designacione', $parametros);

        $this->emit('dataTableRenderDes');

        return view('livewire.admin.registrosdesignacion', compact('resultados'))->with('i', 0);
    }

    protected $listeners = ['finalizar'];

    public function finalizar($id)
    {
        $designacione = Designacione::find($id);
        if ($designacione->estado == false) {
            $this->emit('error', 'La designación ya se encuentra finalizada.');
        } else {
            DB::beginTransaction();
            try {
                $designacione->fechaFin = date('Y-m-d');
                $designacione->estado = false;
                $designacione->save();
                DB::commit();
                $this->emit('success', 'Designación finalizada correctamente');
            } catch (\Throwable $th) {
                DB::rollBack();
                $this->emit('error', 'Ha ocurrido un error');
            }
        }
    }

    public function resetearTurnoExtra()
    {
        $this->reset('fechaInicio', 'turno_extra_id', 'rrhhtipobono_id', 'designacioneSelected');
    }

    public function addTurnoExtra($designacione_id)
    {
        $this->designacioneSelected = Designacione::find($designacione_id);
        $cliente_id = $this->designacioneSelected->turno->cliente_id;
        $this->selCliente_id = $cliente_id;
        $this->turnos = Turno::where('cliente_id', $this->selCliente_id)
            ->where('id', '!=', $this->designacioneSelected->turno_id)
            ->get();

        $this->emit('openModal');
    }

    public function updatedSelClienteId($value)
    {
        $this->turno_extra_id="";
        $horainicio_actual = $this->designacioneSelected->turno->horainicio;
        $horafin_actual    = $this->designacioneSelected->turno->horafin;

        $clienteSelected = Cliente::with('turnos')->find($this->selCliente_id);

        if (!$clienteSelected) {
            $this->turnos = collect();
            return;
        }

        $this->turnos = $clienteSelected->turnos
            ->where('id', '!=', $this->designacioneSelected->turno_id)
            ->filter(function ($turno) use ($horainicio_actual, $horafin_actual) {

                // No hay cruce si:
                // termina antes o empieza después
                return (
                    $turno->horafin <= $horainicio_actual ||
                    $turno->horainicio >= $horafin_actual
                );
            })
            ->values(); // reindexa la colección
    }
    public function registrarTurnoExtra()
    {
        $this->validate([
            'turno_extra_id' => 'required',
            'fechaInicio' => 'required|date',
            'rrhhtipobono_id' => 'required',
            'intervalo' => 'required|numeric|min:1',
        ]);

        DB::beginTransaction();
        try {
            $turno_extra = Turno::find($this->turno_extra_id);
            $fechaFin = $this->fechaInicio;

            if ($turno_extra->horainicio > $turno_extra->horafin) {
                $fechaFin = date('Y-m-d', strtotime($this->fechaInicio . ' +1 day'));
            }

            $designacion = Designacione::create([
                "empleado_id" => $this->designacioneSelected->empleado_id,
                "turno_id" => $this->turno_extra_id,
                "fechaInicio" => $this->fechaInicio,
                "fechaFin" => $fechaFin,
                "intervalo_hv" => $this->intervalo,
                "observaciones" => "Turno extra asignado desde la designación ID: " . $this->designacioneSelected->id,
                "tipo" => 'EXTRA',
            ]);


            $intervalos = [];
            $intervalos = crearIntervalo($turno_extra->horainicio, $turno_extra->horafin, $this->intervalo);

            foreach ($intervalos as $item) {
                Intervalo::create([
                    "designacione_id" => $designacion->id,
                    "hora" => $item,
                ]);
            }
            $numDia = date('N', strtotime($this->fechaInicio));
            $lunes = false;
            $martes = false;
            $miercoles = false;
            $jueves = false;
            $viernes = false;
            $sabado = false;
            $domingo = false;
            switch ($numDia) {
                case '1':
                    $lunes = true;
                    break;
                case '2':
                    $martes = true;
                    break;
                case '3':
                    $miercoles = true;
                    break;
                case '4':
                    $jueves = true;
                    break;
                case '5':
                    $viernes = true;
                    break;
                case '6':
                    $sabado = true;
                    break;
                case '7':
                    $domingo = true;
                    break;
            }

            $dias = Designaciondia::create([
                "designacione_id" => $designacion->id,
                "lunes" => $lunes,
                "martes" => $martes,
                "miercoles" => $miercoles,
                "jueves" => $jueves,
                "viernes" => $viernes,
                "sabado" => $sabado,
                "domingo" => $domingo,
            ]);

            DB::commit();
            $this->emit('success', 'Turno extra registrado correctamente');
            $this->resetearTurnoExtra();
            $this->emit('closeModal');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->emit('error', 'Ha ocurrido un error al registrar el turno extra');
        }
    }
}
