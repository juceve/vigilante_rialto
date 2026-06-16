<?php

namespace App\Http\Livewire\Vigilancia;

use App\Models\Cliente;
use App\Models\Designaciondia;
use App\Models\Designacione;
use App\Models\Intervalo;
use App\Models\Turno;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Activacubrerelevos extends Component
{
    public $cliente_id = "", $turno_id = "", $clientes, $cliente;
    public $lunes = false, $martes = false, $miercoles = false, $jueves = false, $viernes = false, $sabado = false, $domingo = false;

    public function mount()
    {
        $this->clientes = Cliente::all()->pluck('nombre', 'id');
        $this->cliente = new Cliente();
    }

    public function render()
    {
        if ($this->cliente_id != "") {
            $this->turno_id = "";
            $this->cliente = Cliente::find($this->cliente_id);
        }
        return view('livewire.vigilancia.activacubrerelevos')->extends('layouts.app');
    }

    protected $rules = [
        "cliente_id" => "required",
        "turno_id" => "required",
    ];

    public function activarRelevo()
    {
        $this->validate();
        $timestamp = time();
        $numeroDiaSemana = date('w', $timestamp);

        switch ($numeroDiaSemana) {
            case '1':
                $this->lunes = true;
                break;
            case '2':
                $this->martes = true;
                break;
            case '3':
                $this->miercoles = true;
                break;
            case '4':
                $this->jueves = true;
                break;
            case '5':
                $this->viernes = true;
                break;
            case '6':
                $this->sabado = true;
                break;
            case '7':
                $this->domingo = true;
                break;
        }

        DB::beginTransaction();

        try {
            $turno = Turno::find($this->turno_id);
            $designacion = Designacione::create([
                "cliente_id" => $this->cliente_id,
                "turno_id" => $this->turno_id,
                "empleado_id" => Auth::user()->empleados[0]->id,
                "fechaInicio" => date('Y-m-d'),
                "fechaFin" => date('Y-m-d'),
                "intervalo_hv" => 1,
                "observaciones" => "CUBRE RELEVO - Cliente:" . $this->cliente->nombre . " - Turno:" . $turno->nombre,
            ]);


            $intervalo = crearIntervalo($turno->horainicio, $turno->horafin, 1);
            foreach ($intervalo as $item) {
                Intervalo::create([
                    "designacione_id" => $designacion->id,
                    "hora" => $item,
                ]);
            }

            $dias = Designaciondia::create([
                "designacione_id" => $designacion->id,
                "lunes" => $this->lunes,
                "martes" => $this->martes,
                "miercoles" => $this->miercoles,
                "jueves" => $this->jueves,
                "viernes" => $this->viernes,
                "sabado" => $this->sabado,
                "domingo" => $this->domingo,
            ]);

            DB::commit();
            return redirect()->route('home')->with('success', 'Se activó el Relevo correctamente.');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->emit('error', $th->getMessage());
            // $this->emit('error', 'Ha ocurrido un error');
        }
    }
}
