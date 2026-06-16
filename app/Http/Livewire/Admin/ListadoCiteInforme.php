<?php

namespace App\Http\Livewire\Admin;

use App\Models\Citeinforme;
use App\Models\Cliente;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;

class ListadoCiteInforme extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $clientes = null, $selID = "", $cliente = null;

    public $i_cite = "", $i_representante = "", $i_objeto = "", $i_fecha = "", $i_referencia = "", $i_causal = "", $causales = [];

    public $i_cliente = "", $updCiteId = "";


    public $busqueda = "", $filas = 5, $gestion;

    public function render()
    {
        $citeinformes = Citeinforme::where([['cite', 'like', "%$this->busqueda%"], ['gestion', $this->gestion]])
            ->orWhere([["cliente", "like", "%$this->busqueda%"], ['gestion', $this->gestion]])
            ->orWhere([["fecha", "like", "%$this->busqueda%"], ['gestion', $this->gestion]])
            ->orderBy('correlativo', 'DESC')
            ->paginate($this->filas);
        return view('livewire.admin.listado-cite-informe', compact('citeinformes'))->with('i', 1)->extends('adminlte::page');
    }

    protected $listeners = ['anular'];

    public function mount()
    {
        $this->gestion = date('Y');
        $this->clientes = Cliente::all()->pluck('nombre', 'id');
        $this->i_fecha = date('Y-m-d');
    }

    public function updatedBusqueda()
    {
        $this->resetPage();
    }
    public function updatedGestion()
    {
        $this->resetPage();
    }

    public function updatedSelID()
    {
        $this->cliente = Cliente::find($this->selID);
        if ($this->cliente) {
            $this->i_representante = $this->cliente->personacontacto;
        } else {
            $this->i_representante = "";
        }
    }

    public function i_agregarCausal()
    {
        $this->causales[] = $this->i_causal;
        $this->i_causal = "";
    }

    public function delICausal($i)
    {
        unset($this->causales[$i]);
        $this->causales = array_values($this->causales);
    }


    public function previa()
    {
        $data = [];
        $data[] = 0;
        $data[] = $this->i_objeto;
        $data[] = fechaEs($this->i_fecha);
        $data[] = $this->cliente->nombre;
        $data[] = $this->i_representante;
        $data[] = $this->i_referencia;
        $puntos = implode("|", $this->causales);
        $datos = '0|' . $this->i_objeto . '|' . fechaEs($this->i_fecha) . '|' . $this->cliente->nombre . '|' . $this->i_representante . '|' . $this->i_referencia . '^';
        $datos .= $puntos;
        $datos = codGet($datos);
        Session::put('data-citeinforme', $datos);
        $this->emit('renderizarpdf');
    }

    public function resetAll()
    {
        $this->reset('selID', 'cliente', 'i_cite', 'i_representante', 'i_objeto', 'i_fecha', 'i_referencia', 'i_causal', 'causales');
    }

    public function registrar()
    {
        DB::beginTransaction();
        try {
            $last = Citeinforme::where('gestion', date('Y'))->orderBy('correlativo', 'DESC')->first();

            if ($last) {
                $last = $last->correlativo;
                $last++;
            } else {
                $last = 1;
            }
            $puntos = implode("|", $this->causales);
            $citeinforme = Citeinforme::create([
                'correlativo' => $last,
                'gestion' => date('Y'),
                'cite' => "INF-"   . str_pad($last, 3, "0", STR_PAD_LEFT) . "/" . date('Y'),
                'fecha' => $this->i_fecha,
                'fechaliteral' => fechaEs($this->i_fecha),
                'objeto' => $this->i_objeto,
                'cliente' => $this->cliente->nombre,
                'cliente_id' => $this->cliente->id,
                'representante' => $this->i_representante,
                'referencia' => $this->i_referencia,
                'puntos' => $puntos,
            ]);

            DB::commit();

            $this->resetAll();
            $datos = $citeinforme->id."|1";
            $this->emit('renderizarpdf', $datos);
            $this->emit('success', 'Informe registrado correctamente.');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->emit('error', $th->getMessage());
        }
    }

    public function editar($citeinforme_id)
    {
        $citeinforme = Citeinforme::find($citeinforme_id);
        $this->updCiteId = $citeinforme_id;
        $this->i_cliente = $citeinforme->cliente;
        $this->i_representante = $citeinforme->representante;
        $this->i_objeto = $citeinforme->objeto;
        $this->i_fecha = $citeinforme->fecha;
        $this->i_referencia = $citeinforme->referencia;
        $this->causales = explode("|", $citeinforme->puntos);
        $this->cliente = $citeinforme->cliente_data;

        $this->selID = $citeinforme->cliente_id;
    }

    public function actualizar()
    {
        DB::beginTransaction();
        try {

            $puntos = implode("|", $this->causales);
            $citeinforme = Citeinforme::find($this->updCiteId)->update([
                'fecha' => $this->i_fecha,
                'fechaliteral' => fechaEs($this->i_fecha),
                'objeto' => $this->i_objeto,
                'cliente' => $this->cliente->nombre,
                'cliente_id' => $this->cliente->id,
                'representante' => $this->i_representante,
                'referencia' => $this->i_referencia,
                'puntos' => $puntos,
            ]);

            DB::commit();

            $this->resetAll();

            $datos = $this->updCiteId;
            // $this->emit('renderizarpdf', $datos);
            $this->emit('success', 'Informe actualizado correctamente.');
        } catch (\Throwable $th) {

            DB::rollBack();
            $this->emit('error', $th->getMessage());
        }
    }

    public function anular($citeinforme_id)
    {
        DB::beginTransaction();
        try {
            $citeinforme = Citeinforme::find($citeinforme_id)->update([
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
