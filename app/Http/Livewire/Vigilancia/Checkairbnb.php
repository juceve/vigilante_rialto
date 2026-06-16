<?php

namespace App\Http\Livewire\Vigilancia;

use App\Models\Airbnblink;
use App\Models\Airbnbtraveler;
use App\Models\Designacione;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use SebastianBergmann\Environment\Console;

class Checkairbnb extends Component
{
    public $status = '', $mensaje = '', $designacione_id = "", $designacione = null, $search = '';
    public function render()
    {

        $this->designacione = Designacione::find($this->designacione_id);

        return view('livewire.vigilancia.checkairbnb')->extends('layouts.app');
    }

    public $airbnbtraveler; // Variable para mostrar los datos del registro

    public function mount()
    {
        $this->airbnbtraveler = new Airbnbtraveler();
    }

    protected $listeners = ['buscarRegistro', 'activar', 'finalizar'];

    public function buscarCod() {
        if ($this->search!='') {
           $cliente_id = $this->designacione->turno->cliente_id; 
           $airbnbtraveler = Airbnbtraveler::find($this->search);
            if ($airbnbtraveler) {
                if ($airbnbtraveler->airbnblink->cliente_id == $cliente_id) {
                    if ($airbnbtraveler->arrival_date <= date('Y-m-d H:i:s') && $airbnbtraveler->departure_date >= date('Y-m-d H:i:s')) {
                        $this->airbnbtraveler = $airbnbtraveler;
                        $this->status = $this->airbnbtraveler->status;
                    } else {
                        $this->emit('error', 'La fecha está fuera de los limites.');
                    }
                } else {
                    $this->emit('error', 'El registro no corresponde a este establecimiento.');
                }
            } else {
                $this->emit('error', 'No se encuentra el registro.');
            }
        }
    }

    public function resetAll(){        
        $this->airbnbtraveler= new Airbnbtraveler();
    }

    public function buscarRegistro($datos)
    {

        // dd($this->designacione->turno->cliente_id);
        $cliente_id = $this->designacione->turno->cliente_id;

        $data = explode('|', $datos);
        if (count($data) == 3) {
            $airbnbtraveler = Airbnbtraveler::find($data[1]);
            if ($airbnbtraveler) {
                if ($airbnbtraveler->airbnblink->cliente_id == $cliente_id) {
                    if ($airbnbtraveler->arrival_date <= date('Y-m-d H:i:s') && $airbnbtraveler->departure_date >= date('Y-m-d H:i:s')) {
                        $this->airbnbtraveler = $airbnbtraveler;
                        $this->status = $this->airbnbtraveler->status;
                    } else {
                        $this->emit('error', 'La fecha está fuera de los limites.');
                    }
                } else {
                    $this->emit('error', 'El registro no corresponde a este establecimiento.');
                }
            } else {
                $this->emit('error', 'No se encuentra el registro.');
            }
        } else {
            $this->emit('error', 'El formato de los datos no es correcto.');
        }
    }

    public function activar($id)
    {
        DB::beginTransaction();
        try {

            $this->airbnbtraveler->status = 'ACTIVADO';
            $this->airbnbtraveler->reg_in = date('Y-m-d H:i:s');
            $this->airbnbtraveler->save();
            $this->status = 'ACTIVADO';
            $this->mensaje = 'Updated';
            DB::commit();
            $this->emit('success', 'Reserva activada con exito!');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->emit($th->getMessage());
        }
    }
    public function finalizar($id)
    {
        DB::beginTransaction();
        try {

            $this->airbnbtraveler->status = 'FINALIZADO';
            $this->airbnbtraveler->reg_out = date('Y-m-d H:i:s');
            $this->airbnbtraveler->save();
            $this->status = 'FINALIZADO';
            $this->mensaje = 'Updated';
            DB::commit();
            $this->emit('success', 'Visita finalizada con exito!');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->emit($th->getMessage());
        }
    }

    public function copiarAlPortapapeles()
    {
        if ($this->airbnbtraveler->status == 'ACTIVADO') {
            $this->mensaje = "Acaba de realizar su registro de INGRESO al Airbnb el huesped: " . $this->airbnbtraveler->name . " al departamento: " . $this->airbnbtraveler->department_info . " en fecha: " . $this->airbnbtraveler->reg_in;
        } else {
            $this->mensaje = "Acaba de realizar su registro de SALIDA del Airbnb el huesped: " . $this->airbnbtraveler->name . " del departamento: " . $this->airbnbtraveler->department_info . " en fecha: " . $this->airbnbtraveler->reg_out;
        }

        $this->emit('copiarTexto', $this->mensaje);
        session()->flash('success', 'Texto copiado al portapapeles.');
    }

    public function sendWhatsAppLink()
    {
        $this->copiarAlPortapapeles();
        $telefono = '591' . $this->airbnbtraveler->airbnblink->celular; // Reemplaza con el número al que deseas enviar el mensaje

        $whatsappUrl = "https://wa.me/$telefono?text=$this->mensaje";

        $this->emit('open-whatsapp', ['url' => $whatsappUrl]);
    }



    public function exportarPDF()
    {
        $data = $this->airbnbtraveler->toArray();
        $companions_count = $this->airbnbtraveler->airbnbcompanions->count();
        $condominio = $this->airbnbtraveler->airbnblink->cliente->nombre;
        $data['condominio'] = $condominio;
        $data['companions_count'] = $companions_count;
        $companions = array();
        if ($companions_count > 0) {
            foreach ($this->airbnbtraveler->airbnbcompanions as $airbnbcompanion) {
                $companions[] = $airbnbcompanion->toArray();
            }
        }
        $data['companions_data'] = $companions;

        $pdf = Pdf::loadView('pdfs.pdfformularioairbnb', $data);
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'formularioairbnb.pdf');
    }
}
