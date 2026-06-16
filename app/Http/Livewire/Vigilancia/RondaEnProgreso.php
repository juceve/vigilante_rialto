<?php

namespace App\Http\Livewire\Vigilancia;

use App\Models\Designacione;
use App\Models\Rondaejecutadaubicacione;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class RondaEnProgreso extends Component
{
    public $rondaejecutada_id = 0;
    public $latitud;
    public $longitud;

    public $ultLatitud;
    public $ultLongitud;

    protected $listeners = ['registrarUbicacion'];

    public function mount()
    {
        $cliente_id = Session::get('cliente_id-oper');
        $this->rondaejecutada_id = tengoRondaIniciada(Auth::id(), $cliente_id);
    }

    public function render()
    {
        return view('livewire.vigilancia.ronda-en-progreso');
    }
}
