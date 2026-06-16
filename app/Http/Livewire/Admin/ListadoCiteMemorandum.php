<?php

namespace App\Http\Livewire\Admin;

use App\Models\Citememorandum;
use App\Models\Cliente;
use App\Models\Empleado;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;

class ListadoCiteMemorandum extends Component
{
    use WithPagination;

    public $m_cite = "", $selID = "", $m_fecha = "", $m_motivo = "", $updCiteId = "";

    public $empleados = null, $empleado = null, $filas = 5, $gestion;

    public $busqueda = "";
    protected $citememorandums = "";


    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->gestion = date('Y');
        $this->empleados = Empleado::all();
        $this->m_motivo = "Se llama severamente la atención a usted, por no venir a labores en fecha 08/07/14 demostrando en su posicion de una falta total de responsabilidad en sus funciones especificas, recordandole que se hara el descuento de acuerdo a normativa legal vigente estipulada en el ministerio de trabajo en sus haberes del mes de Julio del presente.";
    }

    public function updatedBusqueda()
    {
        $this->resetPage();
    }
    public function updatedGestion()
    {
        $this->resetPage();
    }


    public function resetAll()
    {
        $this->reset('selID', 'm_fecha');
    }

    public function updatedSelID()
    {
        $this->empleado = Empleado::find($this->selID);
    }

    public function render()
    {
        $citememorandums = Citememorandum::where([['cite', 'like', "%$this->busqueda%"], ['gestion', $this->gestion]])
            ->orWhere([["empleado", "like", "%$this->busqueda%"], ['gestion', $this->gestion]])
            ->orWhere([["fecha", "like", "%$this->busqueda%"], ['gestion', $this->gestion]])
            ->orderBy('correlativo', 'DESC')
            ->paginate($this->filas);

        return view('livewire.admin.listado-cite-memorandum', compact('citememorandums'))
            ->extends('adminlte::page');
    }

    protected $listeners = ['anular'];

    protected $rules = [
        'selID' => ' required',
    ];

    public function previa()
    {

        $datos = 0;
        $datos .= "|" . $this->empleado->nombres . " " . $this->empleado->apellidos . "|";
        $datos .= fechaEs($this->m_fecha) . "|";
        $datos .= $this->m_motivo;
        $datos = codGet($datos);
        Session::put('data-citememorandum', $datos);
        $this->emit('renderizarpdf');
    }

    public function registrar()
    {
        $this->validate();

        DB::beginTransaction();
        try {
            $last = Citememorandum::where('gestion', date('Y'))->orderBy('correlativo', 'DESC')->first();

            if ($last) {
                $last = $last->correlativo;
                $last++;
            } else {
                $last = 1;
            }
            $citememo = Citememorandum::create([
                'correlativo' => $last,
                'gestion' => date('Y'),
                'cite' => "MEM-"   . str_pad($last, 3, "0", STR_PAD_LEFT) . "/" . date('Y'),
                'fecha' => $this->m_fecha,
                'fechaliteral' => fechaEs($this->m_fecha),
                'empleado' => $this->empleado->nombres . " " . $this->empleado->apellidos,
                'empleado_id' => $this->empleado->id,
                'cuerpo' => $this->m_motivo,
            ]);

            DB::commit();

            $this->resetAll();
            $datos = $citememo->id;
            $this->emit('renderizarpdf', $datos);
            $this->emit('success', 'Memorandum registrado correctamente.');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->emit('error', $th->getMessage());
        }
    }

    public function editar($citememorandum_id)
    {
        $citememorandum = Citememorandum::find($citememorandum_id);
        $this->updCiteId = $citememorandum_id;
        $this->m_fecha = $citememorandum->fecha;
        $this->m_motivo = $citememorandum->cuerpo;

        $this->selID = $citememorandum->empleado_id;
        $this->empleado = Empleado::find($this->selID);
    }

    public function actualizar()
    {
        DB::beginTransaction();
        try {

            $citememorandum = Citememorandum::find($this->updCiteId)->update([
                'fecha' => $this->m_fecha,
                'fechaliteral' => fechaEs($this->m_fecha),
                'empleado' => $this->empleado->nombres . " " . $this->empleado->apellidos,
                'empleado_id' => $this->empleado->id,
                'cuerpo' => $this->m_motivo,
            ]);

            DB::commit();

            $this->resetAll();

            $this->emit('success', 'Memorandum actualizado correctamente.');
        } catch (\Throwable $th) {

            DB::rollBack();
            $this->emit('error', $th->getMessage());
        }
    }

    public function anular($citememo_id)
    {
        DB::beginTransaction();
        try {
            $citememo = Citememorandum::find($citememo_id)->update([
                'estado' => false,
            ]);
            DB::commit();
            $this->emit('success', 'Cite anulado correctamente.');
        } catch (\Throwable $th) {

            DB::rollBack();
            $this->emit('error', $th->getMessage());
        }
    }
}
