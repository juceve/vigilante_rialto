<?php

namespace App\Http\Livewire\Admin;

use App\Models\Cliente;
use App\Models\Propietario;
use App\Models\Residencia;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Aprobaciones extends Component
{
    public $propietario, $propietario_id, $cliente_id, $cliente;
    public $residenciaSeleccionada = null;
    public $showModal = false;

    public function mount($propietario_id, $cliente_id)
    {
        $this->propietario_id = $propietario_id;
        $this->cliente_id = $cliente_id;
        $this->cliente = $cliente_id ? Cliente::find($cliente_id) : null;
    }

    public function revisar($residenciaId)
    {
        $this->residenciaSeleccionada = Residencia::findOrFail($residenciaId);
        $this->showModal = true;
    }

    public function cerrarModal()
    {
        $this->showModal = false;
        $this->residenciaSeleccionada = null;
    }

    protected $listeners = ['aprobar'];

    public function aprobar()
    {
        $propietario = $this->residenciaSeleccionada->propietario;
        if ($this->residenciaSeleccionada) {
            $this->residenciaSeleccionada->update([
                'cedula_propietario' => $propietario->cedula,
                'estado' => 'VERIFICADO',
            ]);

            $propietario->activo = true;
            $propietario->save();
            if (is_null($propietario->user_id) || $propietario->user_id === "") {
                if (is_null($propietario->email) || $propietario->email === "") {
                    $email = strtolower($propietario->nombre . $propietario->id . "@" . config('app.name'));
                    $email = str_replace(" ", "", $email);
                    $propietario->email = $email;
                } else {
                    $email = strtolower($propietario->email);
                }

                $user = User::create([
                    "name" => $propietario->nombre,
                    // "password" => bcrypt($propietario->cedula),
                    "password" => Hash::make($propietario->cedula),
                    "email" => $email,
                    "template" => "PROPIETARIO",
                    "status" => 1,
                ]);
                $propietario->user_id = $user->id;
                $propietario->save();
            }
            $this->emit('success', 'La residencia fue aprobada correctamente.');
        }
        $this->cerrarModal();
    }

    public function render()
    {
        $this->propietario = Propietario::findOrFail($this->propietario_id);
        $residencias = $this->propietario
            ->residencias()
            ->where('estado', 'CREADO')
            ->where('cliente_id', $this->cliente_id)
            ->get();

        return view('livewire.admin.aprobaciones', compact('residencias'))
            ->extends('adminlte::page');
    }
}
