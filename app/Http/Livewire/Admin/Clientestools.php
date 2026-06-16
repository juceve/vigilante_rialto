<?php

namespace App\Http\Livewire\Admin;

use App\Models\Cliente;
use App\Models\Sistemaparametro;
use App\Models\Vwdesignacione;
use Livewire\Component;

class Clientestools extends Component
{
    public $selCliente = NULL, $designaciones = NULL, $marque = 2, $arrayClientes = [];

    public function render()
    {
        $clientesa = Cliente::select('id', 'nombre', 'oficina_id', 'latitud', 'longitud', 'direccion', 'personacontacto', 'telefonocontacto', 'status','fecha_inicio','fecha_fin')
            ->with('oficina:id,nombre')
            ->where('status', 1)
            ->orderBy('oficina_id', 'ASC')
            ->get();

        $pts = "";
        $clientes = [];
        $hoy = date('Y-m-d');

        // Obtener tolerancia del sistema
        $parametrossistema = Sistemaparametro::first();
        $tolerancia = $parametrossistema ? $parametrossistema->tolerancia_ingreso : 15;

        foreach ($clientesa as $cliente) {
            $alerta = 0;
            $marque = 2; // Por defecto: secondary (sin alertas)

            $designaciones = Vwdesignacione::select('id', 'empleado_id', 'turno_id', 'cliente_id')
                ->with(['datosempleado:id,user_id', 'datosturno:id,horainicio,horafin'])
                ->where([
                    ['cliente_id', $cliente->id],
                    ['fechaInicio', '<=', $hoy],
                    ['fechaFin', '>=', $hoy],
                    ['estado', true],
                ])->get();

            foreach ($designaciones as $item) {
                $resultado = yaMarque2($item->id);
                $estado = $resultado[0];
                $ingreso = $resultado[1];

                switch ($estado) {
                    case 0: // No marcó y YA PASÓ la hora de inicio (CRÍTICO)
                        $alerta = 1;
                        $marque = 0; // danger
                        break 2; // Salir inmediatamente

                    case 1: // Marcó ingreso, pendiente salida (ACTIVO)
                        // Verificar pánicos primero (MÁXIMA PRIORIDAD)
                        $panicos = tengoPanicos($item->datosempleado->user_id, $cliente->id);
                        if ($panicos > 0) {
                            $alerta = 1;
                            $marque = 0; // danger por pánicos
                            break 2;
                        }

                        // Verificar retraso en el ingreso
                        if ($ingreso && $item->datosturno) {
                            $horaInicio = \Carbon\Carbon::parse($item->datosturno->horainicio);
                            $horaIngreso = \Carbon\Carbon::parse($ingreso);

                            $minutosRetraso = 0;
                            if ($horaIngreso->gt($horaInicio)) {
                                $minutosRetraso = $horaIngreso->diffInMinutes($horaInicio);
                            }

                            // Marcó con retraso mayor a tolerancia
                            if ($minutosRetraso > $tolerancia) {
                                if ($marque > 3) {
                                    $marque = 3; // warning
                                }
                            } else {
                                // Marcó puntual
                                if ($marque > 1) {
                                    $marque = 1; // primary
                                }
                            }
                        } else {
                            if ($marque > 1) {
                                $marque = 1; // primary
                            }
                        }
                        break;

                    case 2: // Turno completo
                        if ($marque > 2) {
                            $marque = 2; // secondary
                        }
                        break;

                    case 3: // Aún no es hora (EN DESCANSO)
                        if ($marque > 2) {
                            $marque = 2; // secondary
                        }
                        break;
                }
            }

            $clientes[] = [$cliente->id, $cliente->nombre, $cliente->oficina->nombre, $alerta, $marque];
            $fila = $cliente->nombre . "|" . $cliente->latitud . "|" . $cliente->longitud . "|" .
                    $cliente->direccion . "|" . $cliente->personacontacto . "|" .
                    $cliente->telefonocontacto . "|" . $cliente->id . "|" . $alerta . "|" . $marque;
            $pts .= $fila . "$";
        }

        $pts = substr($pts, 0, -1);
        $this->arrayClientes = $clientes;

        return view('livewire.admin.clientestools', compact('clientes', 'pts', 'tolerancia'));
    }

    public function cargarCliente($cliente_id)
    {
        $this->reset('selCliente', 'designaciones');

        $this->selCliente = Cliente::select('id', 'nombre', 'direccion', 'personacontacto', 'telefonocontacto','fecha_fin')
            ->find($cliente_id);

        $hoy = date('Y-m-d');

        // Optimizar con eager loading
        $this->designaciones = Vwdesignacione::select('id', 'empleado', 'turno', 'empleado_id', 'turno_id', 'cliente_id')
            ->with([
                'datosempleado:id,user_id',
                'datosturno:id,horainicio,horafin',
                'intervalos' => function($query) use ($hoy) {
                    $query->withCount(['hombrevivos' => function($q) use ($hoy) {
                        $q->where('fecha', $hoy)->where('status', true);
                    }]);
                }
            ])
            ->where([
                ['cliente_id', $this->selCliente->id],
                ['fechaInicio', '<=', $hoy],
                ['fechaFin', '>=', $hoy],
                ['estado', true],
            ])->get();

        // Buscar el estado del cliente en el array
        foreach ($this->arrayClientes as $item) {
            if ($item[0] == $cliente_id) {
                $this->marque = $item[4];
                break;
            }
        }
    }
}
