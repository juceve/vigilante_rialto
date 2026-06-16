<?php

namespace App\Http\Livewire;

use App\Models\Citememorandum;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Datatables extends Component
{
    public $tabla, $campos, $titulos, $condiciones;

    public function mount($tabla, $campos, $titulos, $condiciones)
    {
        $this->tabla = $tabla;
        $this->campos = $campos;
        $this->titulos = $titulos;
        $this->condiciones = $condiciones;
    }

    public function render()
    {
        $data = $this->generarConsulta($this->tabla, $this->campos, $this->condiciones);
        return view('livewire.datatables', compact('data'))->with('i', 1);
    }
    public function generarConsulta($tabla, $campos, $condiciones = [])
    {
        $campos1 = [];
        $campos1[] = 'id';

        foreach ($campos as $campo) {
            $campos1[] = $campo;
        }
        // Verifica que la tabla y los campos no estén vacíos
        if (empty($tabla) || empty($campos)) {
            return [];
        }

        // Comienza la construcción de la consulta
        $consulta = DB::table($tabla)->select($campos1);

        // Agrega condiciones WHERE si se proporcionan
        foreach ($condiciones as $campo => $valor) {
            $consulta->where($campo, $valor);
        }

        // Ejecuta la consulta y convierte los resultados a un array
        $resultados = $consulta->get();

        $retornar = [];
        foreach ($resultados as $item => $value) {
            $result = [];
            foreach ($value as $it) {
                $result[] = $it;
            }
            $retornar[] = $result;
        }

        return $retornar;
    }
}
