<?php

namespace App\Http\Livewire\Vigilancia;

use App\Models\Designacione;
use App\Models\Empleado;
use App\Models\Rrhhcontrato;
use App\Models\Rrhhpermiso;
use App\Models\Rrhhtipopermiso;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class Vacaciones extends Component
{
    use WithFileUploads;
    protected $paginationTheme = 'bootstrap';

    public $designacione, $empleado, $contratoActivo, $procesando = false, $permisoSel = null;
    public $rrhhtipopermisos = null, $rrhhtipopermiso_id = '', $fecha_inicio, $fecha_fin, $motivo = '', $file;

    protected $listeners = ['store', 'view', 'anular'];

    public function mount()
    {
        $hoy = Carbon::now()->toDateString();

        $this->designacione = Designacione::find(session('designacion-oper'));
        $empleado_id = $this->designacione ? $this->designacione->empleado_id : null;
        $this->empleado = Empleado::find($empleado_id);

        $this->contratoActivo = Rrhhcontrato::where('empleado_id', $this->empleado->id)
            ->whereDate('fecha_inicio', '<=', $hoy)
            ->where(function ($query) use ($hoy) {
                $query->whereNull('fecha_fin')
                    ->orWhereDate('fecha_fin', '>=', $hoy);
            })
            ->where('activo', true)
            ->orderBy('fecha_inicio', 'asc')
            ->first();



        $this->rrhhtipopermisos = Rrhhtipopermiso::all();
        $this->fecha_inicio = date('Y-m-d');
        $this->fecha_fin = date('Y-m-d');
    }

    public function view($id)
    {
        $this->permisoSel = Rrhhpermiso::find($id);
        if ($this->permisoSel) {
            $this->emit('openModalDetalles');
        } else {
            $this->emit('error', 'Permiso no encontrado');
        }
    }

    public function nuevaSolicitud()
    {
        $this->emit('openModalSolicitud');
    }

    public function store()
    {
        if ($this->procesando) {
            return;
        }
        $this->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'motivo' => 'required|string|max:255',
            'file' => 'nullable|file|max:2048', // Máximo 2MB
        ]);

        $this->procesando = true;
        DB::beginTransaction();
        try {
            $solicitud = Rrhhpermiso::create([
                'rrhhcontrato_id' => $this->contratoActivo->id ?? 0,
                'empleado_id' => $this->empleado->id ?? 0,
                'rrhhtipopermiso_id' => $this->rrhhtipopermiso_id,
                'fecha_inicio' => $this->fecha_inicio,
                'fecha_fin' => $this->fecha_fin,
                'motivo' => $this->motivo,
                'status' => 'SOLICITADO',
            ]);

            if ($this->file) {
                // Obtiene la extensión original del archivo (por ejemplo: pdf, jpg, etc.)
                $extension = $this->file->getClientOriginalExtension();

                // Genera un nombre único basado en el id de la solicitud y un timestamp
                $fileName = 'doc_' . $solicitud->id . '_' . time() . '.' . $extension;

                $directory = storage_path('app/public/documentos/permisos');
                if (!file_exists($directory)) {
                    mkdir($directory, 0775, true);
                }

                // Define la ruta dentro del disco 'public'
                $filePath = $this->file->storeAs('documentos/permisos', $fileName, 'public');

                // Guarda el path en la base de datos
                $solicitud->documento_adjunto = $filePath;
                $solicitud->save();
            }

            DB::commit();

            return redirect()->route('vigilancia.vacaciones')->with('success', 'Solicitud de permiso creada correctamente.');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->procesando = false;
            $this->emit('error', 'Error al crear la solicitud: ' . $th->getMessage());
        }
    }

    public function anular($id)
    {
        $permiso = Rrhhpermiso::find($id);
        if (!$permiso) {
            $this->emit('error', 'Permiso no encontrado');
            return;
        }

        if ($permiso->status !== 'SOLICITADO') {
            $this->emit('error', 'Solo se pueden anular permisos en estado SOLICITADO');
            return;
        }

        try {
            $permiso->activo = false;
            $permiso->save();

            return redirect()->route('vigilancia.vacaciones')->with('success', 'Permiso anulado correctamente.');
        } catch (\Throwable $th) {
            $this->emit('error', 'Error al anular el permiso: ' . $th->getMessage());
        }
    }

    public function render()
    {
        $permisos = Rrhhpermiso::where('rrhhcontrato_id', $this->contratoActivo->id ?? 0)
            ->orderBy('id', 'desc')
            ->paginate(5);

        return view('livewire.vigilancia.vacaciones', compact('permisos'))->extends('layouts.app');
    }
}
