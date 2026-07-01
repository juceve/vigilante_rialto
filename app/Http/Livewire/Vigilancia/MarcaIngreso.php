<?php

namespace App\Http\Livewire\Vigilancia;

use App\Models\Asistencia;
use App\Models\Designacione;
use App\Models\Rrhhcontrato;
use App\Models\Rrhhdescuento;
use App\Models\Rrhhtipodescuento;
use App\Models\Sistemaparametro;
use Carbon\Carbon;
use Livewire\Component;

class MarcaIngreso extends Component
{
    public $lat = "", $lng = "", $designacione_id = "", $designacione = null;
    public $bloqueado = false;
    public $parametrosgenerales, $contrato;

    protected $listeners = ['cargaPosicion'];

    public function mount()
    {
        $this->designacione = Designacione::find($this->designacione_id);
        $this->parametrosgenerales = Sistemaparametro::first();

        $hoy = Carbon::today();

        $this->contrato = Rrhhcontrato::where('empleado_id', $this->designacione->empleado_id)
            ->where('activo', true)
            ->whereDate('fecha_inicio', '<=', $hoy)
            ->where(function ($q) use ($hoy) {
                $q->whereDate('fecha_fin', '>=', $hoy)
                    ->orWhereNull('fecha_fin');
            })
            ->first();
    }

    public function render()
    {

        return view('livewire.vigilancia.marca-ingreso');
    }

    public function marcar()
    {
        $sistemaparametros = Sistemaparametro::get()->first();
        $latCliente = $this->designacione->turno->cliente->latitud;
        $lngCliente = $this->designacione->turno->cliente->longitud;

        if ($this->bloqueado) return;
        $this->bloqueado = true;

        if (!$this->estaDentroDelRango($this->lat, $this->lng, $latCliente, $lngCliente, 100)) {
            $this->bloqueado = false;
            $this->emit('error', 'No estás dentro del área permitida.');
            return;
        }


        $asistencia = Asistencia::create([
            'designacione_id' => $this->designacione_id,
            'fecha' => now()->toDateString(),
            'ingreso' => now(),
            'latingreso' => $this->lat,
            'lngingreso' => $this->lng,
        ]);

        $horainicio = $this->designacione->turno->horainicio;

        // Hora esperada con precisión hasta minutos
        $horaEsperada = Carbon::parse($asistencia->fecha . ' ' . $horainicio)->format('Y-m-d H:i');
        $horaEsperada = Carbon::createFromFormat('Y-m-d H:i', $horaEsperada);

        // Hora real, también truncada a minutos
        $horaReal = Carbon::parse($asistencia->ingreso)->format('Y-m-d H:i');
        $horaReal = Carbon::createFromFormat('Y-m-d H:i', $horaReal);

        $tolerancia = $this->parametrosgenerales->tolerancia_ingreso;

        $minutosRetraso = 0;

        if ($horaReal->greaterThan($horaEsperada)) {
            $minutosRetraso = $horaReal->diffInMinutes($horaEsperada);
        }

        if ($minutosRetraso > ($tolerancia)) {
            $tipodescuento = Rrhhtipodescuento::find($sistemaparametros->ingreso_atrasado);
            $descuento = Rrhhdescuento::create([
                "rrhhcontrato_id" => $this->contrato->id,
                "fecha" => date('Y-m-d'),
                "rrhhtipodescuento_id" => $tipodescuento->id,
                "empleado_id" => $this->designacione->empleado_id,
                "cantidad" => 1,
                "monto" => $tipodescuento->monto,
            ]);
        }

        return redirect()->route('home');
    }

    public function cargaPosicion($data)
    {
        $this->lat = $data[0];
        $this->lng = $data[1];

        // Una vez recibida la posición, recién marcar
        $this->marcar();
    }

    public function estaDentroDelRango($lat1, $lon1, $lat2, $lon2, $radio = 100)
    {
        $distancia = calcularDistancia($lat1, $lon1, $lat2, $lon2);
        return $distancia <= $radio;
    }
}
