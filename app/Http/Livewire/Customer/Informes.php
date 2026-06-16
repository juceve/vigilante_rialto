<?php

namespace App\Http\Livewire\Customer;

use App\Models\Citeinforme;
use App\Models\Cliente;
use App\Models\Usercliente;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Informes extends Component
{

    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $cliente_id;


    public $busqueda = "", $filas = 5, $gestion;

    public function render()
    {
        $citeinformes = Citeinforme::where([['cite', 'like', "%$this->busqueda%"], ['gestion', $this->gestion], ['cliente_id', $this->cliente_id]])
            ->orWhere([["fecha", "like", "%$this->busqueda%"], ['gestion', $this->gestion], ['cliente_id', $this->cliente_id]])
            ->orWhere([["referencia", "like", "%$this->busqueda%"], ['gestion', $this->gestion], ['cliente_id', $this->cliente_id]])
            ->orderBy('correlativo', 'DESC')
            ->paginate(10);
        return view('livewire.customer.informes', compact('citeinformes'))->with('i', 1)->extends('layouts.clientes');
    }

    protected $listeners = ['anular'];

    public function mount()
    {
        $this->gestion = date('Y');
        $usercliente = Usercliente::where('user_id', Auth::user()->id)->first();
        $this->cliente_id = $usercliente->cliente_id;
    }

    public function updatedBusqueda()
    {
        $this->resetPage();
    }
    public function updatedGestion()
    {
        $this->resetPage();
    }
}
