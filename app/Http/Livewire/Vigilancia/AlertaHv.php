<?php

namespace App\Http\Livewire\Vigilancia;

use App\Models\Hombrevivo;
use App\Models\Intervalo;
use Illuminate\Support\Carbon;
use Livewire\Component;

class AlertaHv extends Component
{
    public $tienePendientes = false;
    public $intervaloActual = null;

    public function mount()
    {
        $this->verificarPendientes();
    }

    public function verificarPendientes()
    {
        $designacion_id = session('designacion-oper');

        if (!$designacion_id) {
            $this->tienePendientes = false;
            return;
        }

        $hoy = Carbon::now()->format('Y-m-d');
        $horaActual = Carbon::now()->format('H:i:s');

        // Obtener intervalos ordenados
        $intervalos = Intervalo::where('designacione_id', $designacion_id)
            ->orderBy('hora')
            ->get();

        if ($intervalos->isEmpty()) {
            $this->tienePendientes = false;
            return;
        }

        // Buscar primer intervalo sin marcar cuya hora ya pasó o está cerca
        foreach ($intervalos as $index => $intervalo) {
            // Verificar si ya marcó hoy
            $marcado = Hombrevivo::where('intervalo_id', $intervalo->id)
                ->where('fecha', $hoy)
                ->where('status', true)
                ->exists();

            if ($marcado) {
                continue; // Ya marcó, siguiente
            }

            // Hora del intervalo actual
            $horaIntervalo = substr($intervalo->hora, 0, 5);

            // 15 min antes del intervalo
            $horaAlerta = Carbon::createFromFormat('H:i', $horaIntervalo)->subMinutes(15)->format('H:i:s');

            // Si hay siguiente intervalo, obtener su hora (límite)
            $horaLimite = null;
            if (isset($intervalos[$index + 1])) {
                $horaLimite = $intervalos[$index + 1]->hora;
            }

            // Verificar si debe mostrar alerta
            if ($horaActual >= $horaAlerta) {
                // Si hay límite (siguiente intervalo) y ya lo pasamos, este venció
                if ($horaLimite && $horaActual >= $horaLimite) {
                    continue; // Ya venció, pasar al siguiente
                }

                // Mostrar este intervalo
                $this->intervaloActual = [
                    'id' => $intervalo->id,
                    'hora' => $horaIntervalo,
                    'urgente' => $horaActual > $intervalo->hora
                ];

                $this->tienePendientes = true;
                break; // Solo mostrar uno a la vez
            }
        }

        if (!$this->intervaloActual) {
            $this->tienePendientes = false;
        }
    }

    public function render()
    {
        return view('livewire.vigilancia.alerta-hv');
    }
}
