<?php

namespace App\Http\Livewire\Vigilancia;

use App\Models\Hombrevivo as ModelsHombrevivo;
use App\Models\Intervalo;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class HombreVivo extends Component
{
    public $intervalo = null, $anotaciones = "", $lat = "", $lng = "";
    public $procesando = false;

    public function mount($intervalo)
    {
        if ($intervalo != 0) {
            $this->intervalo = Intervalo::find($intervalo);
        }
    }

    protected $listeners = ['ubicacionAprox'];

    public function render()
    {
        return view('livewire.vigilancia.hombre-vivo')->extends('layouts.app');
    }

    public function reportarse()
    {
        if ($this->procesando) {
            return;
        }

        $this->procesando = true;
        DB::beginTransaction();
        try {
            // dd($this->lat);
            $hombrevivo = ModelsHombrevivo::create([
                'intervalo_id' => $this->intervalo->id,
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i'),
                'anotaciones' => $this->anotaciones,
                'lat' => $this->lat,
                'lng' => $this->lng,
            ]);

            DB::commit();

            return redirect()->route('home')->with('success', 'Reporte realizado con exito.');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->emit('error', 'Ha ocurrido un error');
            $this->procesando = false;
        }
    }

    public function ubicacionAprox($data)
    {
        $this->lat = $data[0];
        $this->lng = $data[1];
        // dd($this->lat);
        // dd($data);
    }
}
