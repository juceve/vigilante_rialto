<?php

namespace App\Http\Livewire\Admin;

use App\Models\Cliente;
use Illuminate\Support\Facades\Crypt;
use Livewire\Component;
use Livewire\WithPagination;

class ListadoClientes extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $busqueda = "", $filas = 10;

    public $linkSolicitud = '';

    public function render()
    {
        $clientes = Cliente::join('oficinas', 'oficinas.id', '=', 'clientes.oficina_id')->select('clientes.*', 'oficinas.nombre AS oficina')
            ->where('clientes.nombre', 'LIKE', '%' . $this->busqueda . '%')
            ->orWhere('clientes.direccion', 'LIKE', '%' . $this->busqueda . '%')
            ->orWhere('oficinas.nombre', 'LIKE', '%' . $this->busqueda . '%')
            ->paginate($this->filas);

        return view('livewire.admin.listado-clientes', compact('clientes'))
            ->with('i', 0);
    }

    public function updatedBusqueda()
    {
        $this->resetPage();
    }
    public function updatedFilas()
    {
        $this->resetPage();
    }

    public function generaLink($cliente_id)
    {
        $encryptedId = Crypt::encryptString($cliente_id);
        $this->linkSolicitud = route('regpropietario', $encryptedId);
        $this->emit('abrirModalLink');
    }
}
