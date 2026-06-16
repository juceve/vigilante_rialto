<?php

namespace App\Http\Livewire;

use App\Models\Asistencia;
use App\Models\Designacione;
use App\Models\Rrhhcontrato;
use App\Models\Rrhhdescuento;
use App\Models\Rrhhtipodescuento;
use App\Models\Sistemaparametro;
use Carbon\Carbon;
use Livewire\Component;

class MarcarIngreso extends Component
{
    public $lat = "", $lng = "", $designacione_id = "", $designacione = null;
    public $bloqueado = false;
    public $parametrosgenerales, $contrato;

    protected $listeners = ['cargaPosicion'];

    public function mount()
    {
        $this->designacione = $this->designacione_id ? Designacione::find($this->designacione_id) : null;

        $this->parametrosgenerales = Sistemaparametro::first();

        $hoy = Carbon::today();

        if ($this->designacione && $this->designacione->empleado_id) {
            $this->contrato = Rrhhcontrato::where('empleado_id', $this->designacione->empleado_id)
                ->where('activo', true)
                ->whereDate('fecha_inicio', '<=', $hoy)
                ->where(function ($q) use ($hoy) {
                    $q->whereDate('fecha_fin', '>=', $hoy)
                        ->orWhereNull('fecha_fin');
                })
                ->first();
        } else {
            $this->contrato = null;
        }
    }

    public function render()
    {

        return view('livewire.marcar-ingreso');
    }

    public function marcar()
    {
        if ($this->bloqueado) return;
        $this->bloqueado = true;

        // Validaciones básicas
        if (!$this->designacione) {
            $this->bloqueado = false;
            session()->flash('error', 'Designación no encontrada.');
            return redirect()->route('home');
        }

        $asistencia = Asistencia::create([
            'designacione_id' => $this->designacione_id,
            'fecha' => now()->toDateString(),
            'ingreso' => now(),
            'latingreso' => $this->lat,
            'lngingreso' => $this->lng,
        ]);

        // Obtener hora de inicio del turno: priorizar Turnoguardia vinculado via designacionturno
        $turno = null;
        if ($this->designacione->designacionturno && $this->designacione->designacionturno->turnoguardia) {
            $turno = $this->designacione->designacionturno->turnoguardia;
        } elseif ($this->designacione->turno) {
            $turno = $this->designacione->turno;
        }

        if (!$turno || empty($turno->horainicio)) {
            $this->bloqueado = false;
            session()->flash('error', 'Información de turno incompleta.');
            return redirect()->route('home');
        }

        $horainicio = $turno->horainicio;

        // Hora esperada con precisión hasta minutos
        $horaEsperada = Carbon::parse($asistencia->fecha . ' ' . $horainicio)->format('Y-m-d H:i');
        $horaEsperada = Carbon::createFromFormat('Y-m-d H:i', $horaEsperada);

        // Hora real, también truncada a minutos
        $horaReal = Carbon::parse($asistencia->ingreso)->format('Y-m-d H:i');
        $horaReal = Carbon::createFromFormat('Y-m-d H:i', $horaReal);

        $tolerancia = $this->parametrosgenerales->tolerancia_ingreso ?? 0;

        $minutosRetraso = 0;

        if ($horaReal->greaterThan($horaEsperada)) {
            $minutosRetraso = $horaReal->diffInMinutes($horaEsperada);
        }

        if ($minutosRetraso > ($tolerancia)) {
            // Solo crear descuento si hay contrato
            if ($this->contrato) {
                $tipodescuento = Rrhhtipodescuento::find(1);
                if ($tipodescuento) {
                    Rrhhdescuento::create([
                        "rrhhcontrato_id" => $this->contrato->id,
                        "fecha" => date('Y-m-d'),
                        "rrhhtipodescuento_id" => $tipodescuento->id,
                        "empleado_id" => $this->designacione->empleado_id,
                        "cantidad" => 1,
                        "monto" => $tipodescuento->monto,
                    ]);
                }
            }
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
}
