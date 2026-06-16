<?php

namespace App\Http\Livewire\Admin;

use App\Exports\VisitasExport;
use App\Models\Cliente;
use App\Models\Visita;
use App\Models\Vwvisita;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Registrosvisita extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $clientes, $cliente_id = "", $estado = "", $inicio, $final, $search = "", $clienteid = "";
    public $visita = null, $imgs = [];

    public function mount()
    {
        $this->inicio = date('Y-m-d');
        $this->final = date('Y-m-d');
        $this->clientes = Cliente::all()->pluck('nombre', 'id');
        $this->visita = new Visita();
    }

    public function render()
    {
        // DB::enableQueryLog();
        $resultados = NULL;
        $sql = "";
        if ($this->clienteid != "") {
            if ($this->estado == "") {
                $resultados = Vwvisita::where([
                    ["fechaingreso", ">=", $this->inicio],
                    ["fechaingreso", "<=", $this->final],
                    ["cliente_id", $this->clienteid],
                    ['visitante', 'LIKE', '%' . $this->search . '%']
                ])->orWhere(
                    [
                        ["fechaingreso", ">=", $this->inicio],
                        ["fechaingreso", "<=", $this->final],
                        ["cliente_id", $this->clienteid],
                        ['residente', 'LIKE', '%' . $this->search . '%']
                    ]
                )->orWhere([
                    ["fechaingreso", ">=", $this->inicio],
                    ["fechaingreso", "<=", $this->final],
                    ["cliente_id", $this->clienteid],
                    ['docidentidad', 'LIKE', '%' . $this->search . '%']
                ])
                    ->orderBy('id', 'DESC')
                    ->paginate(10);
            } else {
                $resultados = Vwvisita::where([
                    ["fechaingreso", ">=", $this->inicio],
                    ["fechaingreso", "<=", $this->final],
                    ["cliente_id", $this->clienteid],
                    ['visitante', 'LIKE', '%' . $this->search . '%'],
                    ["estado", $this->estado],
                ])
                    ->orWhere([
                        ["fechaingreso", ">=", $this->inicio],
                        ["fechaingreso", "<=", $this->final],
                        ["cliente_id", $this->clienteid],
                        ['residente', 'LIKE', '%' . $this->search . '%'],
                        ["estado", $this->estado],
                    ])
                    ->orWhere([
                        ["fechaingreso", ">=", $this->inicio],
                        ["fechaingreso", "<=", $this->final],
                        ["cliente_id", $this->clienteid],
                        ['docidentidad', 'LIKE', '%' . $this->search . '%'],
                        ["estado", $this->estado],
                    ])
                    ->orderBy('id', 'DESC')

                    ->paginate(10);
            }

            $parametros = array($this->clienteid, $this->estado, $this->inicio, $this->final, $this->search);
            Session::put('param-visitas', $parametros);
        }


        return view('livewire.admin.registrosvisita', compact('resultados'))->extends('adminlte::page');
    }

    public function verInfo($id)
    {
        $this->visita = Vwvisita::find($id);
        if ($this->visita->imgs) {
            $this->imgs = explode('|', $this->visita->imgs);
        }
        // $this->imgs = explode('|', $this->visita->imgs);
    }

    public function exporExcel()
    {
        $cliente = Cliente::find($this->clienteid);
        return Excel::download(new VisitasExport(), 'Visitas_' . $cliente->nombre . '_' . date('His') . '.xlsx');
    }

    public function updatedClienteid()
    {
        $this->resetPage();
    }
    public function updatedEstado()
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
    public function updatedSearch()
    {
        $this->resetPage();
    }
}
