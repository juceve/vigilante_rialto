<?php

namespace App\Exports;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class HistorialGuardiasExport implements FromCollection, WithHeadings
{
    protected $request;
    protected $headings = [
        'ID Empleado',
        'Nombre',
        'Lugar',
        'Fecha Inicio',
        'Fecha Final',
        'Turno',
        'Estado',
        'Observaciones'
    ];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    
    public function collection()
    {
        $empleados = $this->request->input('empleados', []);
        $inicio = $this->request->input('inicio');
        $final = $this->request->input('final');

        $query = DB::table('vwdesignaciones')
            ->select(
                'empleado_id',
                'empleado',
                'cliente',
                'fechaInicio',
                'fechaFin',
                'turno',
                DB::raw("CASE WHEN estado = 1 THEN 'Activo' ELSE 'Finalizado' END as estado"),
                'observaciones'
            );

        // Si hay filtros, se aplican
        if (!empty($empleados)) {
            $query->whereIn('empleado_id', $empleados);
        }

        if (!empty($inicio)) {
            $query->whereDate('fechaInicio', '>=', $inicio);
        }

        if (!empty($final)) {
            $query->whereDate('fechaInicio', '<=', $final);
        }

        // Si no se recibe nada, se descarga todo (ya lo hace por defecto)
        return $query->get();
    }

    public function headings(): array
    {
        return [
            ['REPORTE HISTORIAL DE GUARDIAS'], // Título como primera fila
            ['Fecha Inicio: ' . $this->request->inicio], // Título como primera fila
            ['Fecha Final: ' . $this->request->final], // Título como primera fila
            $this->headings
        ];
    }
}
