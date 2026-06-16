<?php

namespace App\Exports;

use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class HombreVivoExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithTitle, WithEvents
{
    protected $resultados;
    protected $fecha_inicio;
    protected $fecha_fin;
    protected $dias;
    protected $filters;

    public function __construct($resultados, $fecha_inicio, $fecha_fin, $filters = [])
    {
        $this->resultados = $resultados;
        $this->fecha_inicio = $fecha_inicio;
        $this->fecha_fin = $fecha_fin;
        $this->filters = $filters;
        
        Carbon::setLocale('es');
        $fechaI = Carbon::parse($fecha_inicio);
        $fechaF = Carbon::parse($fecha_fin);
        $this->dias = [];
        $current = $fechaI->copy();
        
        while ($current <= $fechaF) {
            $this->dias[] = $current->format('d/m');
            $current->addDay();
        }
    }

    public function collection()
    {
        $data = collect();
        
        foreach ($this->resultados as $resultado) {
            $row = [
                $resultado['empleado_nombre'],
                $resultado['total_marcaciones'],
                array_sum(array_column($resultado['dias'], 'esperadas')),
            ];
            
            // Cumplimiento
            $totalEsp = array_sum(array_column($resultado['dias'], 'esperadas'));
            $cump = $totalEsp > 0 ? round(($resultado['total_marcaciones'] / $totalEsp) * 100, 1) : 0;
            $row[] = $cump . '%';
            
            // Datos por día
            foreach ($resultado['dias'] as $dia) {
                if (isset($dia['esperadas']) && $dia['esperadas'] == 0) {
                    $row[] = '-';
                } else {
                    $cant = isset($dia['cant_registros']) ? $dia['cant_registros'] : (isset($dia['cantidad']) ? $dia['cantidad'] : 0);
                    $cump = isset($dia['cumplimiento']) ? $dia['cumplimiento'] : 0;
                    $esp = isset($dia['esperadas']) ? $dia['esperadas'] : 0;
                    $row[] = $cant . '/' . $esp . ' (' . $cump . '%)';
                }
            }
            
            $data->push($row);
        }
        
        // Fila de totales
        $totalMarcaciones = array_sum(array_column($this->resultados, 'total_marcaciones'));
        $totalEsperadas = 0;
        foreach ($this->resultados as $r) {
            $totalEsperadas += array_sum(array_column($r['dias'], 'esperadas'));
        }
        $cumpTotal = $totalEsperadas > 0 ? round(($totalMarcaciones / $totalEsperadas) * 100, 1) : 0;
        
        $totales = ['TOTALES', $totalMarcaciones, $totalEsperadas, $cumpTotal . '%'];
        for ($i = 0; $i < count($this->dias); $i++) {
            $totales[] = '';
        }
        $data->push($totales);
        
        return $data;
    }

    public function headings(): array
    {
        $headers = ['Empleado', 'Total Marcaciones', 'Total Esperadas', 'Cumplimiento'];
        foreach ($this->dias as $dia) {
            $headers[] = $dia;
        }
        return $headers;
    }

    public function styles(Worksheet $sheet)
    {
        $lastColumn = $sheet->getHighestColumn();
        $lastRow = $sheet->getHighestRow();
        
        // Nota: el estilo de encabezado se aplica en AfterSheet porque se insertan filas superiores
        
        // Estilo de la fila de totales
        $sheet->getStyle('A' . $lastRow . ':' . $lastColumn . $lastRow)->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F8F9FA']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ]);
        
        // Centrar columnas
        $sheet->getStyle('B2:' . $lastColumn . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Bordes a todas las celdas
        $sheet->getStyle('A1:' . $lastColumn . $lastRow)->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'DDDDDD']]]
        ]);
        
        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Insertar filas arriba para los filtros
                $insertRows = 4;
                $sheet->insertNewRowBefore(1, $insertRows);

                // Escribir filtros
                $cliente = isset($this->filters['cliente']) ? $this->filters['cliente'] : 'Todos';
                $empleado = isset($this->filters['empleado']) ? $this->filters['empleado'] : 'Todos';
                $fInicio = $this->fecha_inicio;
                $fFin = $this->fecha_fin;

                $sheet->setCellValue('A1', 'Filtros');
                $sheet->setCellValue('A2', 'Cliente');
                $sheet->setCellValue('B2', $cliente);
                $sheet->setCellValue('A3', 'Empleado');
                $sheet->setCellValue('B3', $empleado);
                $sheet->setCellValue('A4', 'Periodo');
                $sheet->setCellValue('B4', $fInicio . ' - ' . $fFin);

                $lastColumn = $sheet->getHighestColumn();
                $lastRow = $sheet->getHighestRow();

                // Encabezado ahora está en la fila ($insertRows + 1)
                $headerRow = $insertRows + 1;
                $sheet->getStyle('A' . $headerRow . ':' . $lastColumn . $headerRow)->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '17A2B8']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                ]);

                // Estilo fila filtros
                $sheet->getStyle('A1:A4')->getFont()->setBold(true);
            }
        ];
    }

    public function columnWidths(): array
    {
        $widths = [
            'A' => 30,
            'B' => 15,
            'C' => 15,
            'D' => 15,
        ];
        
        $columns = range('E', 'Z');
        foreach ($columns as $col) {
            $widths[$col] = 12;
        }
        
        return $widths;
    }

    public function title(): string
    {
        return 'Reporte Hombre Vivo';
    }
}
