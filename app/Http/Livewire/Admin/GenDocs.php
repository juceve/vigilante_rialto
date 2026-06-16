<?php

namespace App\Http\Livewire\Admin;

use App\Models\Cliente;
use Livewire\Component;


class GenDocs extends Component
{
    

    
    public function render()
    {
        return view('livewire.admin.gen-docs')->extends('adminlte::page');
    }

    
}
