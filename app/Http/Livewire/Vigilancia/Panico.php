<?php

namespace App\Http\Livewire\Vigilancia;

use App\Models\Designacione;
use App\Models\Imgregistro;
use App\Models\Registroguardia;
use App\Models\Sistemaparametro;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;
use Intervention\Image\ImageManagerStatic as Image;

class Panico extends Component
{
    use WithFileUploads;

    public $files = [], $informe = "", $conUbicacion = true, $designacion = null, $parametrosgenerales;

    public function mount()
    {
        $this->parametrosgenerales = Sistemaparametro::first();
        $empleado_id = Auth::user()->empleados[0]->id ?? null;

        if ($empleado_id) {
            $this->designacion = Designacione::where('fechaFin', '>=', date('Y-m-d'))
                ->where('empleado_id', $empleado_id)
                ->where('estado', 1)
                ->orderBy('fechaInicio', 'DESC')
                ->first();
        }
    }

    public function render()
    {
        return view('livewire.vigilancia.panico')->extends('layouts.app');
    }

    protected $listeners = ['guardarRegistro', 'registroPanico'];

    public function guardarRegistro($data)
    {
        if ($data) {
            DB::beginTransaction();
            try {
                $registro = Registroguardia::create([
                    "fechahora" => date('Y-m-d H:i:s'),
                    "prioridad" => $data[2],
                    "user_id" => Auth::user()->id,
                    "detalle" => $data[3],
                    "latitud" => $data[0],
                    "longitud" => $data[1],
                    "visto" => true,
                    "cliente_id" => $this->designacion->turno->cliente->id
                ]);
                DB::commit();
            } catch (\Throwable $th) {
                DB::rollBack();
            }
        }
    }


    public function registroPanico($coord)
    {
        try {
            // Validar coordenadas
            if (!$coord || !isset($coord[0]) || !isset($coord[1])) {
                $this->emit('error', 'No se pudieron obtener las coordenadas');
                return;
            }

            // Validar designación
            if (!$this->designacion || !$this->designacion->turno || !$this->designacion->turno->cliente) {
                $this->emit('error', 'No se encontró una designación activa');
                return;
            }

            DB::beginTransaction();

            // Crear registro de pánico
            $registro = Registroguardia::create([
                "fechahora" => now()->format('Y-m-d H:i:s'),
                "prioridad" => 'ALTA',
                "user_id" => Auth::id(),
                "detalle" => $this->informe ?: 'Registro de pánico',
                "latitud" => $coord[0],
                "longitud" => $coord[1],
                "visto" => false, // Cambiado a false para que aparezca como no visto
                "cliente_id" => $this->designacion->turno->cliente->id
            ]);

            // Procesar archivos si existen
            if (!empty($this->files)) {
                $x = 1;
                foreach ($this->files as $file) {
                    try {
                        $extension = $file->getClientOriginalExtension();
                        $name = date('YmdHis') . '_' . $x;
                        $x++;

                        // Crear directorio si no existe
                        $directory = storage_path('app/public/images/registros/panico');
                        if (!file_exists($directory)) {
                            mkdir($directory, 0755, true);
                        }

                        $path = $directory . '/' . $name . '.' . $extension;

                        // Guardar imagen redimensionada
                        Image::make($file->getRealPath())
                            ->resize(600, null, function ($constraint) {
                                $constraint->aspectRatio();
                            })
                            ->save($path);

                        // Crear registro de imagen
                        Imgregistro::create([
                            "registroguardia_id" => $registro->id,
                            "plataforma" => "panico",
                            "url" => 'images/registros/panico/' . $name . '.' . $extension,
                            "tipo" => $extension,
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Error al procesar archivo en pánico: ' . $e->getMessage());
                        // Continuar con los demás archivos
                    }
                }
            }

            DB::commit();

            // Limpiar campos
            $this->reset(['files', 'informe']);

            // Emitir evento de éxito
            $this->emit('success', 'Registro de pánico guardado correctamente');

            // Redireccionar después de un pequeño delay
            return redirect()->route('home')->with('success', 'Registro de Pánico guardado correctamente.');

        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Error en registroPanico: ' . $th->getMessage(), [
                'trace' => $th->getTraceAsString(),
                'coord' => $coord ?? null,
                'user_id' => Auth::id()
            ]);

            $this->emit('error', 'Error al guardar el registro: ' . $th->getMessage());
        }
    }
}
