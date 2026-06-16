<?php

namespace App\Http\Livewire\Vigilancia;

use App\Models\Visita;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Intervention\Image\ImageManagerStatic as Image;

class SalidaVisita extends Component
{
    public $filename, $filesname = [], $visita, $observaciones = "", $imgs = [];

    public function render()
    {
        return view('livewire.vigilancia.salida-visita')->extends('layouts.app');
    }

    protected $listeners = ['deleteInput'];

    public function mount($visita_id)
    {
        $this->visita = Visita::find($visita_id);
        $this->observaciones = $this->visita->observaciones;
        if ($this->visita->imgs) {
            $this->imgs = explode('|', $this->visita->imgs);
        }
    }

    public function marcarSalida()
    {
        DB::beginTransaction();
        try {
            $this->visita->observaciones = $this->observaciones;
            $this->visita->estado = 0;
            $this->visita->save();


            if (count($this->filesname)) {
                $imgs = $this->visita->imgs . "|";
                foreach ($this->filesname as $filename) {
                    $nombreimg = "images/visitas/salida" . rand(1, 10000) . '_' . $this->visita->id . ".png";
                    if (Storage::disk('public')->exists("livewire-tmp/" . $filename)) {
                        Storage::disk('public')->move("livewire-tmp/" . $filename, $nombreimg);
                    }
                    $imgs .= $nombreimg . "|";
                }
                $imgs = substr($imgs, 0, -1);
                $this->visita->imgs = $imgs;
                $this->visita->save();
            }


            DB::commit();
            $designacione_id = $data = Session::get('designacion-oper');
            return redirect()->route('vigilancia.regsalida', $designacione_id)->with('success', 'Registro de Salida exitoso!');
        } catch (\Throwable $th) {
            DB::rollBack();
            $designacione_id = $data = Session::get('designacion-oper');
            return redirect()->route('vigilancia.regsalida', $designacione_id)->with('error', 'Ha ocurrido un error');
        }
    }

    public function cargaImagenBase64($imagebase64)
    {
        $imageData = explode(';base64,', $imagebase64);
        if (count($imageData) == 2) {
            $image = base64_decode($imageData[1]);
            $filename = uniqid() . date('Hms') . '.png';
            // $this->filename = $filename;
            $this->filesname[] = $filename;
            $path = storage_path('app/public/livewire-tmp/' . $filename);

            $img = Image::make($image)->save($path);
        }
    }

    public function deleteInput($id)
    {
        unset($this->filesname[$id]);
    }
}
