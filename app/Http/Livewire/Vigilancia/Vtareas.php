<?php

namespace App\Http\Livewire\Vigilancia;

use App\Models\Designacione;
use App\Models\Tarea;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Intervention\Image\ImageManagerStatic as Image;

class Vtareas extends Component
{
    public  $designacion, $tareas, $search = '', $tarea;
    public $empleado;
    public $photo, $filename, $filesname = [];

    public function mount($designacion)
    {
        $this->designacion = Designacione::find($designacion);
        $this->empleado = $this->designacion->empleado;
        $this->tareas = Tarea::where([
            ["cliente_id", $this->designacion->turno->cliente_id],
            ["empleado_id", $this->designacion->empleado_id],
            ["fecha", date('Y-m-d')],
            ["estado", 1],
        ])->get();
    }

    protected $listeners = ['deleteInput'];

    public function render()
    {
        return view('livewire.vigilancia.vtareas')->extends('layouts.app');
    }

    public function cargarTarea($id)
    {
        $this->tarea = Tarea::find($id);
    }

    public function procesar()
    {
        DB::beginTransaction();

        try {
            $this->tarea->estado = false;
            $this->tarea->save();

            if (count($this->filesname)) {
                $imgs = "";
                foreach ($this->filesname as $filename) {
                    $nombreimg = "images/tareas/" . rand(1, 10000) . '_' . $this->tarea->id . ".png";
                    if (Storage::disk('public')->exists("livewire-tmp/" . $filename)) {

                        Storage::disk('public')->move("livewire-tmp/" . $filename, $nombreimg);
                    }

                    $imgs .= $nombreimg . "|";
                }
                $imgs = substr($imgs, 0, -1);
                $this->tarea->imgs = $imgs;
                $this->tarea->save();
            }

            DB::commit();
            return redirect()->route('vigilancia.tareas', $this->designacion->id)->with('success', 'Tarea finalizada correctamentE.');
        } catch (\Throwable $th) {
            $this->emit('error', 'Ha ocurrido un error.');
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
