<?php

namespace App\Http\Livewire\Vigilancia;

use App\Models\Asistencia;
use App\Models\Designacione;
use DateTime;
use Livewire\Component;

class MarcaSalida extends Component
{
    public $lat = "", $lng = "", $designacione_id = "", $designacione = null;

    public function render()
    {
        $this->designacione = Designacione::find($this->designacione_id);
        return view('livewire.vigilancia.marca-salida');
    }

    protected $listeners = ['cargaPosicion'];

    public function marcar()
    {
        $hoy = date('Y-m-d');
        $ayer = (new DateTime($hoy))->modify('-1 days')->format('Y-m-d');

        if ($this->designacione->turno->horainicio < $this->designacione->turno->horafin) {
            $asistencia = Asistencia::where([
                ['fecha', $hoy],
                ['designacione_id', $this->designacione->id]
            ])->first();
        } else {
            $horaingreso = (new DateTime($hoy . " " . $this->designacione->turno->horainicio))
                ->modify('-1 hours');
            $horaactual = date('H:i');

            if ($horaactual > $horaingreso->format('H:i')) {
                $asistencia = Asistencia::where([
                    ['fecha', $hoy],
                    ['designacione_id', $this->designacione->id]
                ])->first();
            } else {
                $asistencia = Asistencia::where([
                    ['fecha', $ayer],
                    ['designacione_id', $this->designacione->id]
                ])->first();
            }
        }

        if ($asistencia) {
            $asistencia->update([
                'salida' => now(),
                'latsalida' => $this->lat,
                'lngsalida' => $this->lng,
                'estado' => true,
            ]);
            return redirect()->route('home')->with('success', 'Salida registrada correctamente');
        } else {
            $this->emit('error', 'Error: No tiene un marcado de ingreso previo');
        }
    }

    public function cargaPosicion($data)
    {
        $this->lat = $data[0];
        $this->lng = $data[1];

        // Una vez que ya tenemos coordenadas, recién llamamos a marcar
        $this->marcar();
    }
}
