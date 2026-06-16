<?php

namespace App\Http\Livewire\Vigilancia;

use App\Models\Imgnovedade;
use App\Models\Novedade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Intervention\Image\ImageManagerStatic as Image;

class Novedades extends Component
{
    use WithFileUploads;

    public $files = [], $lat = "", $lng = "";
    public $designacion, $informe = "";
    public $photo, $filename, $filesname = [];

    public function mount($designacion)
    {
        $this->designacion = $designacion;
    }

    public function render()
    {
        return view('livewire.vigilancia.novedades')->extends('layouts.app');
    }
    protected $listeners = ['ubicacionAprox', 'deleteInput'];

    public function enviar()
    {
        DB::beginTransaction();
        try {
            $registro = Novedade::create([
                "designacione_id" => $this->designacion,
                "fecha" => date('Y-m-d'),
                "hora" => date('H:i'),
                "contenido" => $this->informe,
                "lat" => $this->lat,
                "lng" => $this->lng,
            ]);

            // $x = 1;
            // foreach ($this->files as $key => $file) {
            //     $arrF = explode('.', $file->getFilename());
            //     $name = date('YmdHis') . $x;

            //     $x++;
            //     $path = $file->storeAs('images/registros/novedades', $name . '.' . $arrF[1]);

            //     $imgreg = Imgnovedade::create([
            //         "novedade_id" => $registro->id,
            //         "url" => $path,
            //         "tipo" => $arrF[1],
            //     ]);
            // }

            $x = 1;
            if (count($this->filesname)) {

                foreach ($this->filesname as $filename) {
                    $name = date('YmdHis') . $x;
                    $nombreimg = 'images/registros/novedades/' . $name . ".png";
                    if (Storage::disk('public')->exists("livewire-tmp/" . $filename)) {

                        Storage::disk('public')->move("livewire-tmp/" . $filename, $nombreimg);
                    }
                    $x++;

                    $imgreg = Imgnovedade::create([
                        "novedade_id" => $registro->id,
                        "url" => $nombreimg,
                        "tipo" => "png",
                    ]);
                }
            }
            DB::commit();

            return redirect()->route('home')->with('success', 'Registro guardado correctamente.');
        } catch (\Throwable $th) {
            DB::rollBack();
            // return redirect()->route('home')->with('error', 'Ha ocurrido un error');
            $this->emit('error', $th->getMessage());
        }
    }

    public function ubicacionAprox($data)
    {
        $this->lat = $data[0];
        $this->lng = $data[1];
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
