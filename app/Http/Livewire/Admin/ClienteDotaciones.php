<?php

namespace App\Http\Livewire\Admin;

use App\Models\Cldotacion;
use App\Models\Cldotaciondetalle;
use App\Models\Cliente;
use App\Models\Propietario;
use App\Models\Residencia;
use App\Models\Rrhhestadodotacion;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ClienteDotaciones extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $cliente_id, $cliente;
    public $imagen = null; // <-- agregar propiedad para wire:model="imagen"
    public $responsable_entrega = ''; // nuevo campo agregado al modelo Cldotacion
    public $search = '';
    public $perPage = 5;
    public $sortField = 'id';
    public $sortDirection = 'desc';
    public $perPageOptions = [5, 10, 15, 25, 50];
    public $filtro_estado = ''; // Nuevo filtro de estado

    public $fecha, $status = 1, $detalle = "", $cantidad = 1, $rrhhestadodotacion_id;
    public $detalles = [];

    public $dotacionSelect = null;

    public $mode = 'create', $procesando = false;

    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['deleteDotacion'];

    public function mount($cliente_id = null)
    {
        $this->fecha = date('Y-m-d');
        $this->cliente_id = $cliente_id;
        $this->cliente = $cliente_id ? Cliente::find($cliente_id) : null;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function updatingfiltro_estado()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $resultados = Cldotacion::where('cliente_id', $this->cliente_id)
            ->where(function ($query) {
                if ($this->filtro_estado !== '') {
                    $query->where('status', $this->filtro_estado);
                }
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
        $estados = Rrhhestadodotacion::all();

        return view('livewire.admin.cliente-dotaciones', compact('resultados', 'estados'))
            ->with('i', 0)
            ->extends('adminlte::page');
    }

    public function resetAll()
    {
        $this->reset('fecha', 'status', 'detalle', 'cantidad', 'rrhhestadodotacion_id', 'detalles', 'mode', 'procesando', 'imagen', 'responsable_entrega');
        // informar al navegador para que limpie el input file y el preview local
        $this->dispatchBrowserEvent('imagen-cleared');
    }

    public function edit($cldotacion_id, $mode)
    {
        $this->resetAll();

        $this->mode = $mode;
        $dotacion = Cldotacion::find($cldotacion_id);
        // guardar instancia en la propiedad para usarla luego en update()
        $this->dotacionSelect = $dotacion;
        if ($dotacion) {
            $this->fecha = $dotacion->fecha;
            $this->responsable_entrega = $dotacion->responsable_entrega ?? '';
            $this->status = $dotacion->status;
            foreach ($dotacion->cldotaciondetalles as $detalle) {
                $rrhhestadodotacion = Rrhhestadodotacion::find($detalle->rrhhestadodotacion_id);
                // incluir url/imagen para previsualización en la vista
                $this->detalles[] = [
                    'detalle' => $detalle->detalle,
                    'cantidad' => $detalle->cantidad,
                    'rrhhestadodotacion_id' => $detalle->rrhhestadodotacion_id,
                    'estado' => $rrhhestadodotacion->nombre,
                    'url' => $detalle->url ?? null,
                    'imagen' => $detalle->imagen ?? null,
                ];
            }
        }

        $this->emit('openModal');
    }

    public function update()
    {
        $this->validate([
            'fecha' => 'required|date',
            'status' => 'required|in:0,1',
            'responsable_entrega' => 'required|string|max:255',
            'detalles' => 'required|array|min:1',
        ]);

        if ($this->procesando) {
            return;
        }
        $this->procesando = true;
        DB::beginTransaction();
        try {
            if (! $this->dotacionSelect || ! $this->dotacionSelect->exists) {
                throw new \Exception('Dotación no cargada para editar.');
            }

            // Actualizar modelo principal
            $this->dotacionSelect->update([
                'fecha' => $this->fecha,
                'status' => $this->status,
                'responsable_entrega' => $this->responsable_entrega,
            ]);

            // Reemplazar detalles
            $this->dotacionSelect->cldotaciondetalles()->delete();
            foreach ($this->detalles as $detalle) {
                $this->dotacionSelect->cldotaciondetalles()->create([
                    'detalle' => $detalle['detalle'],
                    'cantidad' => $detalle['cantidad'],
                    'rrhhestadodotacion_id' => $detalle['rrhhestadodotacion_id'] ?? null,
                    'imagen' => $detalle['imagen'] ?? null,
                    'url' => $detalle['url'] ?? null,
                ]);
            }

            DB::commit();

            $this->resetAll();
            $this->emit('closeModal');
            $this->emit('success', 'Dotación actualizada correctamente');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->emit('error', $th->getMessage() ?: 'Error al actualizar la dotación');
        } finally {
            $this->procesando = false;
        }
    }

    public function create()
    {
        $this->validate([
            'fecha' => 'required|date',
            'status' => 'required|in:0,1',
            'responsable_entrega' => 'required|string|max:255',
            'detalles' => 'required|array|min:1',
        ]);


        if ($this->procesando) {
            return;
        }

        $this->procesando = true;

        DB::beginTransaction();
        try {
            $dotacion = Cldotacion::create([
                'cliente_id' => $this->cliente_id,
                'fecha' => $this->fecha ?? date('Y-m-d'),
                'status' => $this->status,
                'responsable_entrega' => $this->responsable_entrega,
            ]);

            // validar y persistir cada detalle
            foreach ($this->detalles as $index => $detalle) {
                // Validación defensiva por detalle
                if (!is_array($detalle) || empty($detalle['detalle']) || empty($detalle['cantidad'])) {
                    throw new \Exception("Detalle inválido en la posición {$index}");
                }
                // Normalizar claves (evitar undefined index)
                $rrhhId = $detalle['rrhhestadodotacion_id'] ?? null;
                $imagen = $detalle['imagen'] ?? null;
                $url = $detalle['url'] ?? null;

                // crear registro de detalle
                Cldotaciondetalle::create([
                    'cldotacion_id' => $dotacion->id,
                    'detalle' => $detalle['detalle'],
                    'cantidad' => $detalle['cantidad'],
                    'rrhhestadodotacion_id' => $rrhhId,
                    'imagen' => $imagen,
                    'url' => $url,
                ]);
            }

            DB::commit();

            // reset y notificaciones
            $this->resetAll();
            $this->emit('closeModal');
            $this->emit('success', 'Dotación creada correctamente');
        } catch (\Throwable $th) {
            DB::rollBack();
            \Log::error('Error crear dotacion (ClienteDotaciones::create)', ['exception' => $th]);
            $this->emit('error', 'Error al crear la dotación: ' . $th->getMessage());
        } finally {
            // Asegurar que el flag procesando se restaure incluso en error
            $this->procesando = false;
        }
    }

    public function addDetalle()
    {
        // Validar solo los campos del detalle antes de agregar
        $this->validate(array_merge([
            'detalle' => 'required|string|max:255',
            'cantidad' => 'required|integer|min:1',
            'rrhhestadodotacion_id' => 'required|exists:rrhhestadodotacions,id',
        ],
        // validar imagen opcional
        $this->imagen ? ['imagen' => 'nullable|image|max:2048'] : []
        ), [
            'detalle.required' => 'La descripción del detalle es obligatoria.',
            'cantidad.required' => 'La cantidad es obligatoria.',
            'cantidad.integer' => 'La cantidad debe ser un número entero.',
            'cantidad.min' => 'La cantidad debe ser al menos 1.',
            'rrhhestadodotacion_id.required' => 'Seleccione el estado del artículo.',
            'rrhhestadodotacion_id.exists' => 'El estado seleccionado no es válido.',
        ]);

        $estadoDotacion = Rrhhestadodotacion::find($this->rrhhestadodotacion_id);
        $row = [
            'detalle' => strtoupper($this->detalle),
            'cantidad' => $this->cantidad,
            'rrhhestadodotacion_id' => $estadoDotacion->id,
            'estado' => $estadoDotacion->nombre,
            'url' => null,
            'imagen' => null,
        ];

        // Si hay imagen temporal subida por Livewire, comprimir y guardarla en disk 'public'
        if ($this->imagen) {
            $ext = $this->imagen->getClientOriginalExtension() ?: 'jpg';
            $filename = 'cl_' . time() . '_' . Str::uuid() . '.' . $ext;
            // compress and store (<= 200 KB target)
            $path = $this->compressAndStoreUploaded($this->imagen, 'images/dotaciones-clientes', $filename, 200);
            $url = $path ? ('storage/' . $path) : null;
            $row['imagen'] = $filename;
            $row['url'] = $url;
            // resetear la propiedad imagen para nuevas cargas
            $this->reset('imagen');
            // notificar al navegador que borre el input file y preview
            $this->dispatchBrowserEvent('imagen-cleared');
        }

        $this->detalles[] = $row;
        // Resetear campos y errores específicos
        $this->reset('detalle', 'cantidad', 'rrhhestadodotacion_id');
        $this->resetValidation(['detalle', 'cantidad', 'rrhhestadodotacion_id']);
    }

    /**
     * Comprime un UploadedFile a JPEG y lo guarda en disk 'public' en $dir con $filename.
     * Retorna la ruta relativa dentro del disk (por ejemplo "images/dotaciones-clientes/xxx.jpg") o null.
     */
    private function compressAndStoreUploaded($uploadedFile, $dir, $filename, $maxKb = 200)
    {
        try {
            $tmpIn = $uploadedFile->getRealPath();
            if (!file_exists($tmpIn)) return null;

            $mime = $uploadedFile->getClientMimeType() ?: mime_content_type($tmpIn);
            // crear resource desde el archivo
            $src = null;
            switch (strtolower($mime)) {
                case 'image/jpeg':
                case 'image/jpg':
                    $src = @imagecreatefromjpeg($tmpIn);
                    break;
                case 'image/png':
                    $src = @imagecreatefrompng($tmpIn);
                    break;
                case 'image/gif':
                    $src = @imagecreatefromgif($tmpIn);
                    break;
                case 'image/webp':
                    if (function_exists('imagecreatefromwebp')) $src = @imagecreatefromwebp($tmpIn);
                    break;
                default:
                    $data = @file_get_contents($tmpIn);
                    $src = $data ? @imagecreatefromstring($data) : null;
            }
            if (!$src) return null;

            $w = imagesx($src);
            $h = imagesy($src);
            $maxDim = 1600;
            $scale = 1;
            if (max($w, $h) > $maxDim) {
                $scale = $maxDim / max($w, $h);
            }
            $newW = max(1, (int)round($w * $scale));
            $newH = max(1, (int)round($h * $scale));

            $dst = imagecreatetruecolor($newW, $newH);
            // preserve transparency for PNG/GIF by filling with transparent background
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
            $transparent = imagecolorallocatealpha($dst, 255, 255, 255, 127);
            imagefilledrectangle($dst, 0, 0, $newW, $newH, $transparent);
            imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $w, $h);

            // generar temp file y reducir calidad progresivamente
            $tmpOut = tempnam(sys_get_temp_dir(), 'imgc');
            $quality = 90;
            imagejpeg($dst, $tmpOut, $quality);
            $sizeKb = filesize($tmpOut) / 1024;
            while ($sizeKb > $maxKb && $quality > 30) {
                $quality -= 10;
                imagejpeg($dst, $tmpOut, $quality);
                clearstatcache(true, $tmpOut);
                $sizeKb = filesize($tmpOut) / 1024;
            }
            // if still too big, downscale more
            $reduceScale = 0.9;
            while ($sizeKb > $maxKb && ($newW > 400 && $newH > 400)) {
                $newW = max(100, (int)round($newW * $reduceScale));
                $newH = max(100, (int)round($newH * $reduceScale));
                $tmpDst = imagecreatetruecolor($newW, $newH);
                imagealphablending($tmpDst, false);
                imagesavealpha($tmpDst, true);
                $transparent = imagecolorallocatealpha($tmpDst, 255, 255, 255, 127);
                imagefilledrectangle($tmpDst, 0, 0, $newW, $newH, $transparent);
                imagecopyresampled($tmpDst, $dst, 0, 0, 0, 0, $newW, $newH, imagesx($dst), imagesy($dst));
                imagejpeg($tmpDst, $tmpOut, max(25, $quality - 10));
                imagedestroy($tmpDst);
                clearstatcache(true, $tmpOut);
                $sizeKb = filesize($tmpOut) / 1024;
            }

            // guardar en storage public
            $relativePath = trim($dir, '/') . '/' . $filename;
            $content = file_get_contents($tmpOut);
            \Storage::disk('public')->put($relativePath, $content);

            // cleanup
            if (file_exists($tmpOut)) @unlink($tmpOut);
            imagedestroy($dst);
            imagedestroy($src);

            return $relativePath;
        } catch (\Throwable $e) {
            \Log::error('compressAndStoreUploaded error', ['exception' => $e]);
            return null;
        }
    }

    public function removeDetalle($i)
    {
        if (isset($this->detalles[$i])) {
            // elimina el elemento y reindexa el array
            array_splice($this->detalles, $i, 1);
        }
    }

    public function deleteDotacion($cldotacion_id)
    {
        $dotacion = Cldotacion::find($cldotacion_id);
        if ($dotacion) {
            DB::beginTransaction();
            try {
                // Eliminar detalles asociados
                $dotacion->cldotaciondetalles()->delete();
                // Eliminar dotación
                $dotacion->delete();

                DB::commit();
                $this->emit('success', 'Dotación eliminada correctamente');
            } catch (\Throwable $th) {
                DB::rollBack();
                $this->emit('error', 'Error al eliminar la dotación');
            }
        } else {
            $this->emit('error', 'Dotación no encontrada');
        }
    }

    public function actaPDF($cldotacion_id)
    {
        $dotacion = Cldotacion::find($cldotacion_id);
        if ($dotacion) {
            Session::put('dotacion_acta', $dotacion);
           $this->emit('renderizarpdf');
        } else {
            $this->emit('error', 'Dotación no encontrada');
        }
    }
}
