<?php

namespace App\Http\Livewire\Admin;

use App\Models\Designacione;
use App\Models\Empleado;
use Livewire\Component;

class Admrondas extends Component
{
    public $operadores = [], $operador = "", $resultados = null, $fechaI = "", $fechaF = "";

    public function mount()
    {
        $operadores = Empleado::all();
        foreach ($operadores as $operador) {
            if ($operador->area->template == "OPER") {
                $this->operadores[] = array("id" => $operador->id, "nombre" => $operador->nombres . " " . $operador->apellidos);
            }
        }

        $this->fechaI = date('Y-m-d');
        $this->fechaF = date('Y-m-d');
    }

    protected $rules = [
        "operador" => "required"
    ];

    public function render()
    {
        return view('livewire.admin.admrondas')->extends('adminlte::page');
    }

    public function buscar()
    {
        $this->validate();

        if ($this->operador != "") {
            $this->resultados = Designacione::where([
                ['empleado_id', $this->operador],
                ['fechaInicio','<=',$this->fechaI],
                ['fechaFin','>=',$this->fechaF]
            ])
            ->get();
            dd($this->resultados);
        }
    }
}
