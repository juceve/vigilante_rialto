<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Designacione;
use App\Models\Dialibre;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class CronogramaMensualEmpleados extends Component
{
	public $month;
	public $year;
	public $daysInMonth = [];
	public $employees = [];
	public $years = []; // Años disponibles en el filtro

	public function mount()
	{
		$now = Carbon::now();
		$this->month = $now->month;
		$this->year = $now->year;
		$this->calculateMonthDays();
		$this->loadAvailableYears();
	}

	protected function calculateMonthDays()
	{
		try {
			$start = Carbon::create($this->year, $this->month, 1);
			$this->daysInMonth = range(1, $start->daysInMonth);
		} catch (\Exception $e) {
			$this->daysInMonth = range(1, 31);
		}
	}

	protected function loadAvailableYears()
	{
		// Obtener los años de las designaciones
		$years = Designacione::selectRaw('YEAR(fechaInicio) as year_start, YEAR(fechaFin) as year_end')
			->get()
			->flatMap(function ($row) {
				$start = $row->year_start ?? null;
				$end = $row->year_end ?? null;
				return collect([$start, $end])->filter();
			})
			->unique()
			->sort()
			->values();

		if ($years->isNotEmpty()) {
			$this->years = $years->merge([$years->last() + 1])->unique()->sort()->values()->toArray();
		} else {
			$this->years = [Carbon::now()->year];
		}
	}

	public function updatedMonth()
	{
		$this->calculateMonthDays();
	}

	public function updatedYear()
	{
		$this->calculateMonthDays();
	}

	public function loadData()
	{
		$this->calculateMonthDays();
		$this->employees = [];

		try {
			$startDate = Carbon::create($this->year, $this->month, 1)->startOfDay();
			$endDate = Carbon::create($this->year, $this->month, 1)->endOfMonth()->endOfDay();
		} catch (\Exception $e) {
			$this->emit('error', 'Error al crear las fechas del rango');
			return;
		}

		try {
			// Obtener designaciones que intersectan con el mes/año seleccionado
			$designaciones = Designacione::with('empleado')
				->where(function($q) use ($startDate, $endDate) {
					$q->where('fechaInicio', '<=', $endDate->toDateString())
					  ->where(function($q2) use ($startDate) {
						  $q2->whereNull('fechaFin')
						     ->orWhere('fechaFin', '>=', $startDate->toDateString());
					  });
				})
				->get();

			if ($designaciones->isEmpty()) {
				$this->employees = [];
				$this->emit('info', 'No se encontraron designaciones vigentes para este período');
				return;
			}

			// Formar el array multidimensional con empleados y sus días libres
			$result = [];
			foreach ($designaciones as $des) {
				$empleado = $des->empleado;

				if (!$empleado) {
					continue; // Saltar si no hay empleado asociado
				}

				$name = trim(($empleado->nombres ?? '') . ' ' . ($empleado->apellidos ?? ''));
				if (empty($name)) {
					$name = "Empleado #{$empleado->id}";
				}

				// Consultar los días libres de esta designación
				$diasLibres = Dialibre::where('designacione_id', $des->id)
					->whereBetween('fecha', [$startDate->toDateString(), $endDate->toDateString()])
					->pluck('fecha')
					->map(function ($fecha) {
						return Carbon::parse($fecha)->day;
					})
					->toArray();

				// Si el empleado ya está en el array, combinar sus días libres
				$existingIndex = array_search($name, array_column($result, 'name'));
				if ($existingIndex !== false) {
					$result[$existingIndex]['days'] = array_unique(array_merge($result[$existingIndex]['days'], $diasLibres));
				} else {
					$result[] = [
						'name' => $name,
						'days' => $diasLibres,
					];
				}
			}

			// Ordenar empleados por nombre
			usort($result, function ($a, $b) {
				return strcmp($a['name'], $b['name']);
			});

			$this->employees = $result;

			if (!empty($this->employees)) {
				$this->emit('success', 'Cronograma cargado correctamente: ' . count($this->employees) . ' empleado(s)');
			}

		} catch (\Throwable $th) {
			$this->employees = [];
			// \Log::error('Error en CronogramaMensualEmpleados: ' . $th->getMessage());
			$this->emit('error', 'Error al cargar los datos: ' . $th->getMessage());
		}
	}

	public function render()
	{
		return view('livewire.admin.cronograma-mensual-empleados', [
			'daysInMonth' => $this->daysInMonth,
			'employees' => $this->employees,
			'years' => $this->years,
		])->extends('adminlte::page');
	}

    public function exportarPDF() {
        $data = [
            'daysInMonth' => $this->daysInMonth,
            'employees' => $this->employees,
            'year' => $this->year,
            'month' => $this->month,
        ];
        Session::put('cronograma_data', $data);

        $this->emit('renderizarpdf');
    }
}
