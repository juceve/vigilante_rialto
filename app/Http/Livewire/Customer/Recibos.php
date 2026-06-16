<?php

namespace App\Http\Livewire\Customer;

use App\Models\Citerecibo;
use App\Models\Usercliente;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Recibos extends Component
{

    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $busqueda = "", $filas = 5, $gestion, $cliente_id;

    public function render()
    {

        $citerecibos = Citerecibo::where([['cite', 'like', "%$this->busqueda%"], ['gestion', $this->gestion], ['cliente_id', $this->cliente_id]])
            ->orWhere([["mescobro", "like", "%$this->busqueda%"], ['gestion', $this->gestion], ['cliente_id', $this->cliente_id]])
            ->orderBy('correlativo', 'DESC')
            ->paginate($this->filas);

        return view('livewire.customer.recibos', compact('citerecibos'))->extends('layouts.clientes');
    }

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
