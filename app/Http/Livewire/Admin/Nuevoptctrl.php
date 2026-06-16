<?php

namespace App\Http\Livewire\Admin;

use App\Models\Cliente;
use Livewire\Component;

class Nuevoptctrl extends Component
{
    public $cliente;

    public function mount($cliente_id){
        $this->cliente = Cliente::find($cliente_id);
    }
    public function render()
    {
        return view('livewire.admin.nuevoptctrl');
    }
}
