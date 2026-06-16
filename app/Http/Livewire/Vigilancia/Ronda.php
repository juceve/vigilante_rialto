<?php

namespace App\Http\Livewire\Vigilancia;

use App\Models\Designacione;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Ronda extends Component
{
    public $designacion = NULL;
    public function render()
    {


        $empleado_id = Auth::user()->empleados[0]->id;
        $designacion = null;
        $puntos = null;

        $cliente = null;
        if ($empleado_id) {
            $designacion = Designacione::where('fechaFin', '>=', date('Y-m-d'))->where('empleado_id', $empleado_id)->orderBy('fechaInicio', 'ASC')->first();
            if ($designacion) {
                $this->designacion = $designacion;

                $cliente = $designacion->turno->cliente;
            }
        }
        $ronda_id = tengoRondaIniciada(Auth::user()->id, $this->designacion->turno->cliente_id);
        if ($ronda_id) {
            redirect()->route('vigilancia.recorrido_ronda', $ronda_id);
        }
        return view('livewire.vigilancia.ronda', compact('cliente'))->extends('layouts.app');
    }

    protected $listeners = ['iniciarRonda'];

    public function iniciarRonda($ronda_id, $latitud, $longitud)
    {
        if (tengoRondaIniciada(Auth::user()->id, $this->designacion->turno->cliente_id) != 0) {
            $this->emit('error', 'Ya tienes una ronda iniciada. Debes finalizarla para iniciar una nueva.');
        } else {
            $rondaejecutada = \App\Models\Rondaejecutada::create([
                'ronda_id' => $ronda_id,
                'cliente_id' => $this->designacion->turno->cliente_id,
                'user_id' => Auth::user()->id,
                'inicio' => date('Y-m-d H:i:s'),
                'latitud_inicio' => $latitud,
                'longitud_inicio' => $longitud,
                'status' => 'EN_PROGRESO'
            ]);
            return redirect()->route('vigilancia.recorrido_ronda', $rondaejecutada->id)->with('success', 'Ronda iniciada correctamente.');
        }
    }
}
