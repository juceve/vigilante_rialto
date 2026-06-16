<?php

namespace App\Http\Livewire\Admin;

use App\Exports\ResumenOperacionalExport;
use App\Models\Cliente;
use App\Models\Designacione;
use App\Models\Flujopase;
use App\Models\Hombrevivo;
use App\Models\Novedade;
use App\Models\Registroguardia;
use App\Models\Rondaejecutada;
use App\Models\Tarea;
use App\Models\Visita;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class ResumenOpercional extends Component
{
    public $cliente = null, $cliente_id = '', $fechaInicio, $fechaFin, $resultados = [];

    public function mount()
    {
        $this->fechaInicio = date('Y-m-01');
        $this->fechaFin = date('Y-m-t');
    }

    public function render()
    {
        $clientes = Cliente::where('status', 1)->pluck('nombre', 'id');
        return view('livewire.admin.resumen-opercional', compact('clientes'))->extends('adminlte::page');
    }

    public function generarResumen()
    {
        $clientes = Cliente::where('status', 1)
            ->when($this->cliente_id != '', function ($query) {
                $query->where('id', $this->cliente_id);
            })
            ->get();

        $resultados = [];
        foreach ($clientes as $cliente) {
            $rondas = Rondaejecutada::whereDate('inicio', '>=', $this->fechaInicio)
                ->whereDate('inicio', '<=', $this->fechaFin)
                ->where('cliente_id', $cliente->id)
                ->count();

            $visitas = Visita::whereDate('created_at', '>=', $this->fechaInicio)
                ->whereDate('created_at', '<=', $this->fechaFin)
                ->whereHas('designacione.turno.cliente', function ($query) use ($cliente) {
                    $query->where('id', $cliente->id); // Asegurarse de usar el ID correcto
                })
                ->count();

            $flujopases = Flujopase::whereDate('fecha', '>=', $this->fechaInicio)
                ->whereDate('fecha', '<=', $this->fechaFin)
                ->where('tipo', 'INGRESO')
                ->whereHas('paseingreso.residencia.cliente', function ($query) use ($cliente) {
                    $query->where('id', $cliente->id); // Asegurarse de usar el ID correcto
                })
                ->count();

            $tareas = Tarea::whereDate('fecha', '>=', $this->fechaInicio)
                ->whereDate('fecha', '<=', $this->fechaFin)
                ->where('cliente_id', $cliente->id) // Filtro directo por cliente_id
                ->count();

            $panicos = Registroguardia::whereDate('created_at', '>=', $this->fechaInicio)
                ->whereDate('created_at', '<=', $this->fechaFin)
                ->where('cliente_id', $cliente->id) // Filtro directo por cliente_id
                ->count();

            $novedades = Novedade::whereDate('fecha', '>=', $this->fechaInicio)
                ->whereDate('fecha', '<=', $this->fechaFin)
                ->whereHas('designacione.turno.cliente', function ($query) use ($cliente) {
                    $query->where('id', $cliente->id); // Asegurarse de usar el ID correcto
                })
                ->count();

            $hombrevivos = Hombrevivo::whereDate('fecha', '>=', $this->fechaInicio)
                ->whereDate('fecha', '<=', $this->fechaFin)
                ->whereHas('intervalo.designacione.turno.cliente', function ($query) use ($cliente) {
                    $query->where('id', $cliente->id); // Asegurarse de usar el ID correcto
                })
                ->count();

            $resultados[] = [
                'cliente' => $cliente->nombre,
                'rondas' => $rondas,
                'visitas' => $visitas,
                'flujopases' => $flujopases,
                'panicos' => $panicos,
                'tareas' => $tareas,
                'novedades' => $novedades,
                'hombrevivos' => $hombrevivos,
            ];
        }
        Session::put('resumen_operacional_data', $resultados);

        $this->resultados = $resultados;
    }

    public function exportarExcel()
    {
        Session::put('resumen_operacional_parametros', [$this->cliente_id, $this->fechaInicio, $this->fechaFin]);
        return Excel::download(new ResumenOperacionalExport(), 'Resumen_Operacional_' . date('His') . '.xlsx');
    }
}
