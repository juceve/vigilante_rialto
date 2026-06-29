<?php

namespace App\Http\Livewire;

use App\Models\Asistencia;
use App\Models\Designacione;
use App\Models\Rrhhdescuento;
use App\Models\Rrhhtipodescuento;
use DateTime;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class MarcarSalida extends Component
{
    public $lat = "", $lng = "", $designacione_id = "", $designacione = null;

    public function render()
    {
        $this->designacione = Designacione::find($this->designacione_id);
        return view('livewire.marcar-salida');
    }

    protected $listeners = ['cargaPosicion'];

    public function marcar($marcacionAntes)
    {
        $hoy = date('Y-m-d');
        $ayer = (new DateTime($hoy))->modify('-1 days')->format('Y-m-d');

        if ($this->designacione->designacionturno->turnoguardia->horainicio < $this->designacione->designacionturno->turnoguardia->horafin) {
            $asistencia = Asistencia::where([
                ['fecha', $hoy],
                ['designacione_id', $this->designacione->id]
            ])->first();
        } else {
            $horaingreso = (new DateTime($hoy . " " . $this->designacione->designacionturno->turnoguardia->horainicio))
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
            Session::put('asistencia_operador', false);
            if ($marcacionAntes) {
                $contrato = traeContratoActivoEmpleadoId($this->designacione->empleado_id);
                $tipodescuento = Rrhhtipodescuento::find(23);
                $descuento = Rrhhdescuento::create(
                    [
                        'rrhhcontrato_id' => $contrato->id,
                        'fecha' => date('Y-m-d'),
                        'rrhhtipodescuento_id' => 23,
                        'empleado_id' => $this->designacione->empleado_id,
                        'cantidad' => 1,
                        'monto' => $tipodescuento->monto,
                    ]
                );
            }
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
        $this->marcar($data[2]);
    }
}
