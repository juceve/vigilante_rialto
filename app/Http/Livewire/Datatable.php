<?php

namespace App\Http\Livewire;

use App\Exports\VisitasExport;
use App\Models\Cliente;
use App\Models\Visita;
use App\Models\Vwvisita;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Datatable extends Component
{

    public $clientes, $cliente_id = "", $estado = "", $inicio, $final, $search = "";
    public $visita = null;

    public function mount()
    {
        $this->inicio = date('Y-m-d');
        $this->final = date('Y-m-d');
        $this->clientes = Cliente::all()->pluck('nombre', 'id');
        $this->visita = new Visita();
        $this->emit('recargar-datatable');
    }

    public function render()
    {
        // DB::enableQueryLog();

        $resultados = NULL;
        $sql = "";
        if ($this->cliente_id != "") {

            if ($this->estado == "") {

                $resultados = Vwvisita::where([
                    ["fechaingreso", ">=", $this->inicio],
                    ["fechaingreso", "<=", $this->final],
                    ["cliente_id", $this->cliente_id],
                    ['visitante', 'LIKE', '%' . $this->search . '%']
                ])->orWhere(
                    [
                        ["fechaingreso", ">=", $this->inicio],
                        ["fechaingreso", "<=", $this->final],
                        ["cliente_id", $this->cliente_id],
                        ['residente', 'LIKE', '%' . $this->search . '%']
                    ]
                )->orWhere([
                    ["fechaingreso", ">=", $this->inicio],
                    ["fechaingreso", "<=", $this->final],
                    ["cliente_id", $this->cliente_id],
                    ['docidentidad', 'LIKE', '%' . $this->search . '%']
                ])
                    ->orderBy('id', 'ASC')
                    ->get();
            } else {
                $resultados = Vwvisita::where([
                    ["fechaingreso", ">=", $this->inicio],
                    ["fechaingreso", "<=", $this->final],
                    ["cliente_id", $this->cliente_id],
                    ['visitante', 'LIKE', '%' . $this->search . '%'],
                    ["estado", $this->estado],
                ])
                    ->orWhere([
                        ["fechaingreso", ">=", $this->inicio],
                        ["fechaingreso", "<=", $this->final],
                        ["cliente_id", $this->cliente_id],
                        ['residente', 'LIKE', '%' . $this->search . '%'],
                        ["estado", $this->estado],
                    ])
                    ->orWhere([
                        ["fechaingreso", ">=", $this->inicio],
                        ["fechaingreso", "<=", $this->final],
                        ["cliente_id", $this->cliente_id],
                        ['docidentidad', 'LIKE', '%' . $this->search . '%'],
                        ["estado", $this->estado],
                    ])
                    ->orderBy('id', 'ASC')

                    ->get();
            }

            $parametros = array($this->cliente_id, $this->estado, $this->inicio, $this->final, $this->search);
            Session::put('param-visitas', $parametros);
            $this->emit('recargar-datatable');
        }


        return view('livewire.datatable', compact('resultados'))->with('i', 1)->extends('adminlte::page');
    }

    public function verInfo($id)
    {
        $this->visita = Vwvisita::find($id);
    }

    public function exporExcel()
    {
        $cliente = Cliente::find($this->cliente_id);
        return Excel::download(new VisitasExport(), 'Visitas_' . $cliente->nombre . '_' . date('His') . '.xlsx');
    }
}
