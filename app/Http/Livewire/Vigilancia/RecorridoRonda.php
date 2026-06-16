<?php

namespace App\Http\Livewire\Vigilancia;

use App\Models\Rondapunto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;

class RecorridoRonda extends Component
{
    public $ronda_ejecutada, $ronda, $ronda_id, $cliente;

    public $puntos = [];
    public $puntosJs; // JSON preparado para la vista

    public function mount($rondaejecutada_id)
    {
        $this->ronda_ejecutada = \App\Models\Rondaejecutada::find($rondaejecutada_id);
        $this->ronda = $this->ronda_ejecutada->ronda;
        if ($this->ronda && $this->ronda->cliente) {
            $this->puntos = Rondapunto::where('ronda_id', $this->ronda->id)->get();
            $this->cliente = $this->ronda->cliente;
        }

        // Determinar puntos ya marcados (si la relación existe en el modelo Rondaejecutada)
        $marcadosIds = [];
        if ($this->ronda_ejecutada && method_exists($this->ronda_ejecutada, 'rondaejecutadaubicaciones')) {
            // 'rondaejecutadaubicaciones' debe ser la relación que guarda los pasos registrados para esta ejecución
            $marcadosIds = $this->ronda_ejecutada->rondaejecutadaubicaciones()->pluck('rondapunto_id')->toArray();
        }

        // Preparar JSON seguro de puntos para la plantilla (incluye flag 'marcado')
        $this->puntosJs = $this->puntos->map(function ($p) use ($marcadosIds) {
            return [
                'id' => $p->id,
                'lat' => (float) $p->latitud,
                'lng' => (float) $p->longitud,
                'desc' => $p->descripcion,
                'marcado' => in_array($p->id, $marcadosIds),
            ];
        })->values()->toJson();
    }

    public function render()
    {
        return view('livewire.vigilancia.recorrido-ronda')->extends('layouts.app');
    }

    protected $listeners = ['finalizarRonda', 'registrarPunto'];

    public function finalizarRonda($latitud, $longitud)
    {
        DB::beginTransaction();

        try {
            // Lógica para finalizar la ronda
            $this->ronda_ejecutada->fin = now();
            $this->ronda_ejecutada->latitud_fin = $latitud;
            $this->ronda_ejecutada->longitud_fin = $longitud;
            $this->ronda_ejecutada->status = 'FINALIZADA';
            $this->ronda_ejecutada->save();
            DB::commit();
            return redirect()->route('home')->with('success', 'Ronda finalizada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->emit('error', $e->getMessage());
            return;
        }

    }

    // Nuevo: recibir el marcado de un punto desde el cliente (guardando si es posible y evitando duplicados)
    public function registrarPunto($puntoId, $latitud, $longitud)
    {
        DB::beginTransaction();
        try {
            // Primero, intentamos usar la relación 'rondaejecutadaubicaciones' si existe en el modelo
            if ($this->ronda_ejecutada && method_exists($this->ronda_ejecutada, 'rondaejecutadaubicaciones')) {
                // Bloqueo lógico para evitar duplicados por concurrencia
                $exists = $this->ronda_ejecutada->rondaejecutadaubicaciones()->where('rondapunto_id', $puntoId)->lockForUpdate()->exists();
                if ($exists) {
                    DB::commit();
                    $this->emit('puntoDuplicado', ['id' => $puntoId]);
                    return;
                }

                $this->ronda_ejecutada->rondaejecutadaubicaciones()->create([
                    'rondapunto_id' => $puntoId,
                    'latitud' => $latitud,
                    'longitud' => $longitud,
                    'fecha_hora' => now(),
                    // 'user_id' => auth()->id(), // opcional si aplica
                ]);
            }


            DB::commit();

            // Refrescar marcados (para que al recargar la vista mount recoja los cambios)
            $marcadosIds = [];
            if (method_exists($this->ronda_ejecutada, 'rondaejecutadaubicaciones')) {
                $marcadosIds = $this->ronda_ejecutada->rondaejecutadaubicaciones()->pluck('rondapunto_id')->toArray();
            } else {
                // si usamos fallback, leer desde la tabla detectada
                if (!empty($usedTable) && Schema::hasTable($usedTable)) {
                    $marcadosIds = DB::table($usedTable)
                        ->where('rondaejecutada_id', $this->ronda_ejecutada->id)
                        ->pluck('rondapunto_id')
                        ->toArray();
                }
            }

            // actualizar puntosJs para consistencia si el componente se re-renderiza
            $this->puntosJs = $this->puntos->map(function ($p) use ($marcadosIds) {
                return [
                    'id' => $p->id,
                    'lat' => (float) $p->latitud,
                    'lng' => (float) $p->longitud,
                    'desc' => $p->descripcion,
                    'marcado' => in_array($p->id, $marcadosIds),
                ];
            })->values()->toJson();

            // Notificar al cliente para actualizar UI en tiempo real
            $this->emit('puntoRegistradoCliente', ['id' => $puntoId, 'lat' => $latitud, 'lng' => $longitud]);
            return;
        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('Error registrando punto: '.$e->getMessage(), ['punto'=>$puntoId]);
            $this->emit('puntoRegistroError', ['id'=>$puntoId, 'message'=>$e->getMessage()]);
            return;
        }
    }
}
