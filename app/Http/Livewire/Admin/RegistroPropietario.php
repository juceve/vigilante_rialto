<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Propietario;
use App\Models\Residencia;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class RegistroPropietario extends Component
{


    // Propietario
    public $nombre = '';
    public $cedula = '';
    public $telefono = '';
    public $email = '';
    public $direccion = '';
    public $ciudad = '';
    public $existePropietario = true;
    public $residencias = [];
    public $numeropuerta = '';
    public $piso = '';
    public $calle = '';
    public $nrolote = '';
    public $manzano = '';
    public $notas = '';
    public $procesando = false;
    // 'activo' no se muestra: lo seteamos en false al guardar

    // Residencias dinámicas (JSON desde la vista)
    public $residencias_json = '';
    public $cliente;

    public $clienteIdEncrypted; // pública, para enlaces y referencias en URL
    protected $clienteId;        // desencriptado, solo uso interno

    public function mount($clienteId)
    {
        $this->clienteIdEncrypted = $clienteId;
        $this->clienteId = Crypt::decryptString($clienteId);
        $this->cliente = \App\Models\Cliente::find($this->clienteId);
        $this->residencias_json = json_encode([[
            'numeropuerta' => '',
            'piso'         => '',
            'calle'        => '',
            'nrolote'      => '',
            'manzano'      => '',
            'notas'        => '',
        ]]);
    }
    protected $rules = [
        'nombre'    => 'required|string|max:100',
        'cedula'    => 'required|string|max:20',
        'telefono'  => 'nullable|string|max:15',
        'email'     => 'nullable|email|max:100',
        'direccion' => 'nullable|string|max:255',
        'ciudad'    => 'nullable|string|max:100',
    ];
    public function save()
    {
        if ($this->procesando === false) {
            $this->validate();

            // if (count($this->residencias) === 0) {
            //     $this->emit('toast-warning', 'Debes llenar al menos una residencia con datos válidos.');
            //     return;
            // }
            $this->procesando = true;
            DB::beginTransaction();
            try {
                // Verificar si ya existe un propietario con la misma cédula
                $propietario = Propietario::where('cedula', $this->cedula)->first();
                $clienteId = Crypt::decryptString($this->clienteIdEncrypted);
                if (!$propietario) {
                    // Si no existe, crear nuevo propietario
                    $propietario = Propietario::create([
                        'nombre'    => $this->nombre,
                        'cedula'    => $this->cedula,
                        'telefono'  => $this->telefono ?: null,
                        'email'     => $this->email ?: null,
                        'direccion' => $this->direccion ?: null,
                        'ciudad'    => $this->ciudad ?: null,
                        'cliente_id'    => $clienteId,
                        'activo'    => false,
                    ]);
                }

                // $residenciasIds = [];
                // foreach ($this->residencias as $r) {
                //     $residencia = Residencia::create([
                //         'cliente_id'         => $clienteId,
                //         'propietario_id'     => $propietario->id,
                //         'cedula_propietario' => $this->cedula,
                //         'numeropuerta'       => $r['numeropuerta'] ?: null,
                //         'piso'               => $r['piso'] ?: null,
                //         'calle'              => $r['calle'] ?: null,
                //         'nrolote'            => $r['nrolote'] ?: null,
                //         'manzano'            => $r['manzano'] ?: null,
                //         'notas'              => $r['notas'] ?: null,
                //     ]);
                //     $residenciasIds[] = $residencia->id;
                // }

                DB::commit();

                // Guardar los IDs en la sesión
                // Session::put('residencias_registradas', $residenciasIds);

                $encryptedId = Crypt::encryptString($propietario->id);
                // Redirigir al resumen del propietario
                return redirect()->route('propietario.resumen', $encryptedId);
            } catch (\Throwable $e) {
                DB::rollBack();
                $this->emit('toast-error', $e->getMessage());
                $this->procesando = false;
            }
        }
    }


    public function render()
    {
        return view('livewire.admin.registro-propietario')->extends('layouts.registros');
    }

    public function updatedCedula()
    {
        $this->buscarPropietario();
    }

    public function buscarPropietario()
    {
        $this->validate([
            'cedula' => 'required|string|max:20',
        ]);
        $this->reset('nombre', 'telefono', 'email', 'direccion', 'ciudad');

        $propietario = Propietario::where('cedula', $this->cedula)->first();

        if ($propietario) {
            $this->nombre = $propietario->nombre;
            $this->telefono = $propietario->telefono;
            $this->email = $propietario->email;
            $this->direccion = $propietario->direccion;
            $this->ciudad = $propietario->ciudad;
            $this->residencias_json = json_encode($propietario->residencias);
            $this->existePropietario = true;
            $this->emit('toast-warning', 'Propietario ya existe.');
        } else {
            $this->emit('toast-success', 'Propietario nuevo.');
            $this->existePropietario = false;
        }
    }

    public function addResidencia()
    {
        if ($this->numeropuerta != '' || $this->piso != '' || $this->calle != '' || $this->nrolote != '' || $this->manzano != '' || $this->notas != '') {
            $this->residencias[] = [
                'numeropuerta' => $this->numeropuerta,
                'piso' => $this->piso,
                'calle' => $this->calle,
                'nrolote' => $this->nrolote,
                'manzano' => $this->manzano,
                'notas' => $this->notas,
            ];
            $this->reset('numeropuerta', 'piso', 'calle', 'nrolote', 'manzano', 'notas');
        } else {
            $this->emit('toast-warning', 'Debes llenar al menos un campo para agregar una residencia.');
        }
    }

    public function delResidencia($index)
    {
        unset($this->residencias[$index]);
        $this->residencias = array_values($this->residencias); // Reindexar el array
    }
}
