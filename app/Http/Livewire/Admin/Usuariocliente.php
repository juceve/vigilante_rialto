<?php

namespace App\Http\Livewire\Admin;

use App\Models\Cliente;
use App\Models\User;
use App\Models\Usercliente;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Usuariocliente extends Component
{
    public $cliente, $email, $password, $usercliente;

    public function mount($cliente_id)
    {
        $this->cliente = Cliente::find($cliente_id);
        $this->email = $this->cliente->email;
        $this->password = $this->cliente->nrodocumento;
        $this->usercliente = Usercliente::where('cliente_id', $this->cliente->id)->first();
    }
    public function render()
    {
        return view('livewire.admin.usuariocliente')->extends('adminlte::page');
    }

    protected $rules = [
        "email" => "email|required",
        "password" => "required|min:6",
    ];

    public function registrar()
    {
        $this->validate();
        DB::beginTransaction();
        try {
            $usuario = User::create([
                "name" => $this->cliente->nombre,
                "email" => $this->email,
                "password" => bcrypt($this->password),
                "template" => 'CLIENTE',
                "status" => true
            ]);

            $this->cliente->email = $this->email;
            $this->cliente->save();

            $this->usercliente = Usercliente::create([
                "user_id" => $usuario->id,
                "cliente_id" => $this->cliente->id,
            ]);

            DB::commit();
            return redirect()->route('clientes.index')->with('success', 'Usuario creado correctamente');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('clientes.index')->with('error', 'Ha ocurrido un error');
        }
    }

    protected $listeners = ['eliminar'];

    public function eliminar()
    {
        DB::beginTransaction();
        try {
            $usuario = User::find($this->usercliente->user_id);
            $usuario->delete();

            $this->usercliente->delete();

            DB::commit();
            return redirect()->route('clientes.index')->with('success', 'Usuario eliminado correctamente.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('clientes.index')->with('error', 'Ha ocurrido un error.');
        }
    }
}
