<?php

namespace App\Http\Livewire\Admin;

use App\Models\Cliente;
use App\Models\Registroguardia;
use App\Models\Vwpanico;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Regactividad extends Component
{
    public $prioridad = "", $fechahora = "", $user = "", $visto = "", $detalle = "", $imagenes = null;
    public $clientes, $cliente_id = "",  $inicio, $final, $search = "", $visto_id = "";

    public  $lat = "", $lng = "";

    public function mount($cliente_id = NULL)
    {
        $this->inicio = date('Y-m-d');
        $this->final = date('Y-m-d');
        $this->clientes = Cliente::all()->pluck('nombre', 'id');
        if (!is_null($cliente_id)) {
            $this->cliente_id = $cliente_id;
            $this->visto_id = 0;
        }
    }
    public function render()
    {
        $actividades = NULL;
        $sql = "";
        if ($this->cliente_id != "") {

            if ($this->visto_id == "") {

                $actividades = Vwpanico::where([
                    ["fecha", ">=", $this->inicio],
                    ["fecha", "<=", $this->final],
                    ["cliente_id", $this->cliente_id],
                    ['prioridad', 'LIKE', '%' . $this->search . '%']
                ])->orWhere(
                    [
                        ["fecha", ">=", $this->inicio],
                        ["fecha", "<=", $this->final],
                        ["cliente_id", $this->cliente_id],
                        ['detalle', 'LIKE', '%' . $this->search . '%']
                    ]
                )
                    ->orderBy('id', 'ASC')
                    ->get();
            } else {
                $actividades = Vwpanico::where([
                    ["fecha", ">=", $this->inicio],
                    ["fecha", "<=", $this->final],
                    ["cliente_id", $this->cliente_id],
                    ['prioridad', 'LIKE', '%' . $this->search . '%'],
                    ["visto", $this->visto_id],
                ])
                    ->orWhere([
                        ["fecha", ">=", $this->inicio],
                        ["fecha", "<=", $this->final],
                        ["cliente_id", $this->cliente_id],
                        ['detalle', 'LIKE', '%' . $this->search . '%'],
                        ["visto", $this->visto_id],
                    ])
                    ->orderBy('id', 'ASC')
                    ->get();
            }
            $parametros = array($this->cliente_id, $this->inicio, $this->final, $this->search);
            Session::put('param-panico', $parametros);
        }

        $this->emit('dataTableRenderDes');
        return view('livewire.admin.regactividad', compact('actividades'))->extends('adminlte::page');
    }

    public function cargaMensaje($id)
    {
        $registro = Registroguardia::find($id);
        $this->reset('imagenes');
        $this->imagenes = $registro->imgregistros;
        $this->prioridad = $registro->prioridad;
        $this->fechahora = $registro->fechahora;
        $this->lat = $registro->latitud;
        $this->lng = $registro->longitud;
        $this->user = $registro->user->name;
        if ($registro->visto) {
            $this->visto = 'Revisado';
        } else {
            $this->visto = 'Sin Revisar';
        }
        $this->detalle = $registro->detalle;
        $this->prioridad = $registro->prioridad;
        $registro->visto = true;
        $registro->save();
    }
}
