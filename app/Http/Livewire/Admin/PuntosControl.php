<?php

namespace App\Http\Livewire\Admin;

use App\Models\Ctrlpunto;
use App\Models\Turno;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PuntosControl extends Component
{
    public $turno_id, $turno;
    public $nombre = "", $hora = "", $latitud = "", $longitud = "", $pnts = "";

    public function mount($turno_id)
    {
        $this->turno_id = $turno_id;
        $this->turno = Turno::find($turno_id);
    }

    protected $rules = [
        "nombre" => "required",
        "hora" => "required",
        "latitud" => "required",
        "longitud" => "required",
    ];

    protected $listeners = ['registrarPunto', 'delete', 'cargaLatLng'];

    public function render()
    {
        $puntos = Ctrlpunto::where('turno_id', $this->turno_id)->orderBy('hora', 'ASC')->get();
        $turno = Turno::find($this->turno_id);
        $cliente = $turno->cliente;
        foreach ($puntos as $punto) {
            $fila = $punto->nombre . "|" . $punto->latitud . "|" . $punto->longitud;
            $this->pnts .= $fila . "$";
        }
        $this->pnts = substr($this->pnts, 0, -1);

        return view('livewire.admin.puntos-control', compact('puntos', 'turno', 'cliente'))->with('i', 1)->extends('adminlte::page');
    }

    public function cargaLatLng($data)
    {
        $this->latitud = $data[0];
        $this->longitud = $data[1];
    }


    public function registrarPunto()
    {
        $this->validate();
        $this->emit('loading');
        DB::beginTransaction();
        try {
            $punto = Ctrlpunto::create([
                "nombre" => $this->nombre,
                "hora" => $this->hora,
                "latitud" => $this->latitud,
                "longitud" => $this->longitud,
                "turno_id" => $this->turno_id
            ]);

            DB::commit();
            $this->reset('nombre', 'hora', 'latitud', 'longitud');
            $this->emit('unLoading');
            $this->emit('ocultar');
            $this->emit('success', 'Punto registrado correctamente');
        } catch (\Throwable $th) {

            DB::rollBack();
            $this->emit('unLoading');
            $this->emit('error', 'Ha ocurrido un error');
        }
    }

    public function delete($id)
    {
        $this->emit('loading');
        DB::beginTransaction();
        try {
            $punto = Ctrlpunto::find($id)->delete();
            DB::commit();
            $this->emit('unLoading');
            redirect()->route('puntoscontrol', $this->turno_id)->with('success', 'Punto eliminado correctamente.');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->emit('unLoading');
            // $this->emit('error', 'Ha ocurrido un error');
            $this->emit('error', $th->getMessage());
        }
    }
}
