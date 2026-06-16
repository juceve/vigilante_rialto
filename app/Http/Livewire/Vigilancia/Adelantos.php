<?php

namespace App\Http\Livewire\Vigilancia;

use App\Models\Designacione;
use App\Models\Empleado;
use App\Models\Rrhhadelanto;
use App\Models\Rrhhcontrato;
use App\Models\Rrhhpermiso;
use App\Models\Rrhhtipopermiso;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class Adelantos extends Component
{
    use WithFileUploads;
    protected $paginationTheme = 'bootstrap';

    public $designacione, $empleado, $contratoActivo, $procesando = false, $adelantoSel = null;
    public $monto = '', $motivo = '', $file;
    public $selMes = '', $selGestion = '', $meses = [], $gestionesAdelantos = [];

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

        $this->meses = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre'
        ];
        $this->selGestion = Carbon::now()->year;
        $this->selMes = Carbon::now()->month;
        $this->gestionesAdelantos = Rrhhadelanto::where('rrhhcontrato_id', $this->contratoActivo->id ?? 0)
            ->select(DB::raw('DISTINCT YEAR(fecha) as gestion'))
            ->orderBy('gestion', 'desc')
            ->pluck('gestion')
            ->toArray();
    }

    public function view($id)
    {
        $this->adelantoSel = Rrhhadelanto::find($id);
        if ($this->adelantoSel) {
            $this->emit('openModalDetalles');
        } else {
            $this->emit('error', 'Adelanto no encontrado');
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
            'monto' => 'required|numeric|min:0.01',
            'motivo' => 'required|string|max:255',
            'file' => 'nullable|file|max:2048', // Máximo 2MB
        ]);

        $this->procesando = true;
        DB::beginTransaction();
        try {
            $solicitud = Rrhhadelanto::create([
                'rrhhcontrato_id' => $this->contratoActivo->id ?? 0,
                'empleado_id' => $this->empleado->id ?? 0,
                'monto' => $this->monto,
                'fecha' => date('Y-m-d'),
                'mes' => date('m'),
                'motivo' => $this->motivo,
                'estado' => 'SOLICITADO',
            ]);

            if ($this->file) {
                // Obtiene la extensión original del archivo (por ejemplo: pdf, jpg, etc.)
                $extension = $this->file->getClientOriginalExtension();

                // Genera un nombre único basado en el id de la solicitud y un timestamp
                $fileName = 'doc_' . $solicitud->id . '_' . time() . '.' . $extension;

                $directory = storage_path('app/public/documentos/adelantos');
                if (!file_exists($directory)) {
                    mkdir($directory, 0775, true);
                }

                // Define la ruta dentro del disco 'public'
                $filePath = $this->file->storeAs('documentos/adelantos', $fileName, 'public');

                // Guarda el path en la base de datos
                $solicitud->documento_adjunto = $filePath;
                $solicitud->save();
            }

            DB::commit();

            return redirect()->route('vigilancia.adelantos')->with('success', 'Solicitud de adelanto creada correctamente.');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->procesando = false;
            $this->emit('error', 'Error al crear la solicitud: ' . $th->getMessage());
        }
    }

    public function anular($id)
    {
        $adelanto    = Rrhhadelanto::find($id);
        if (!$adelanto) {
            $this->emit('error', 'Adelanto no encontrado');
            return;
        }

        if ($adelanto->estado !== 'SOLICITADO') {
            $this->emit('error', 'Solo se pueden anular adelantos en estado SOLICITADO');
            return;
        }

        try {
            $adelanto->activo = false;
            $adelanto->save();

            return redirect()->route('vigilancia.adelantos')->with('success', 'Adelanto anulado correctamente.');
        } catch (\Throwable $th) {
            $this->emit('error', 'Error al anular el adelanto: ' . $th->getMessage());
        }
    }

    public function render()
    {
        $adelantos = Rrhhadelanto::where('rrhhcontrato_id', $this->contratoActivo->id ?? 0)
            ->when($this->selMes && $this->selGestion, function ($query) {
                $query->whereMonth('fecha', $this->selMes)
                    ->whereYear('fecha', $this->selGestion);
            })
            ->orderBy('id', 'desc')
            ->paginate(5);

        return view('livewire.vigilancia.adelantos', compact('adelantos'))->extends('layouts.app');
    }
}
