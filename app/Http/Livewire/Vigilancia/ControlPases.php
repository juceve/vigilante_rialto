<?php

namespace App\Http\Livewire\Vigilancia;

use App\Models\Airbnblink;
use App\Models\Airbnbtraveler;
use App\Models\Designacione;
use App\Models\Paseingreso;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use SebastianBergmann\Environment\Console;

class ControlPases extends Component
{
    public $status = '', $mensaje = '', $designacione_id = "", $designacione = null, $search = '';
    public function render()
    {

        $this->designacione = Designacione::find($this->designacione_id);

        return view('livewire.vigilancia.control-pases')->extends('layouts.app');
    }

    public $airbnbtraveler; // Variable para mostrar los datos del registro

    public $paseingreso = NULL;

    public function mount()
    {
        $this->airbnbtraveler = new Airbnbtraveler();
    }

    protected $listeners = ['buscarRegistro', 'activar', 'finalizar'];

    public function buscarCod()
    {

        if ($this->search != '') {
            $cliente_id = $this->designacione->turno->cliente_id;
            $paseingreso = Paseingreso::find($this->search);
            if ($paseingreso) {
                if ($paseingreso->residencia->cliente_id == $cliente_id) {
                    if ($paseingreso->fecha_inicio <= date('Y-m-d') && $paseingreso->fecha_fin >= date('Y-m-d')) {
                        if ($paseingreso->estado) {
                            return redirect()->route('vigilancia.detallepase', ['designacione_id' => $this->designacione_id, 'pase_id' => $paseingreso->id]);
                        } else {
                            $this->emit('warning', 'Pase Finalizado.');
                        }
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

    public function resetAll()
    {
        $this->airbnbtraveler = new Airbnbtraveler();
    }

    public function buscarRegistro($datos)
    {


        $paseingreso_id = Crypt::decrypt($datos);
        $paseingreso = Paseingreso::find($paseingreso_id);
        // dd($paseingreso);
        $cliente_id = $this->designacione->turno->cliente_id;



        if ($paseingreso) {
            if ($paseingreso->residencia->cliente_id === $cliente_id) {
                if ($paseingreso->fecha_inicio <= date('Y-m-d') && $paseingreso->fecha_fin >= date('Y-m-d')) {

                    if ($paseingreso->estado) {
                        return redirect()->route('vigilancia.detallepase', ['designacione_id' => $this->designacione_id, 'pase_id' => $paseingreso_id]);
                    } else {
                        $this->emit('warning', 'Pase Finalizado.');
                    }
                } else {
                    $this->emit('warning', 'La fecha está fuera de los limites.');
                }
            } else {
                $this->emit('error', 'El registro no corresponde a este establecimiento.');
            }
        } else {
            $this->emit('error', 'No se encuentra el registro.');
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
