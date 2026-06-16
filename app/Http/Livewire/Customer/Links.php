<?php

namespace App\Http\Livewire\Customer;

use App\Models\Airbnblink;
use App\Models\Airbnbtraveler;
use App\Models\Usercliente;
use App\Models\Vwvisita;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Links extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public  $cliente_id = "",  $inicio, $final, $search = "";
    public $solicitante = "", $cedula = "", $celular = "", $observaciones = "";
    public $airbnblink;

    public function mount()
    {
        $usercliente = Usercliente::where('user_id', Auth::user()->id)->first();
        $this->inicio = date('Y-m-d');
        $this->final = date('Y-m-d');

        $this->cliente_id = $usercliente->cliente_id;
        $this->airbnblink= new Airbnblink();
    }

    public function render()
    {
        // DB::enableQueryLog();
        $resultados = NULL;
        $sql = "";
        if ($this->cliente_id != "") {
            if ($this->search) {
                $resultados = Airbnblink::where("cliente_id", $this->cliente_id)
                ->whereDate("created_at", ">=", $this->inicio)
                ->whereDate("created_at", "<=", $this->final)
                ->where('solicitante','LIKE','%'.$this->search.'%')
                ->orderBy('created_at', 'DESC')
                ->paginate(10);
            } else {
                $resultados = Airbnblink::where("cliente_id", $this->cliente_id)
                ->whereDate("created_at", ">=", $this->inicio)
                ->whereDate("created_at", "<=", $this->final)
                ->orderBy('created_at', 'DESC')
                ->paginate(10);
            }            
            
        }


        return view('livewire.customer.links', compact('resultados'))->extends('layouts.clientes');
    }

    public function verInfo($id)
    {
        $this->airbnblink = Airbnblink::find($id);
    }


    public function nuevoReg()
    {
        $this->reset('solicitante', 'cedula', 'celular','observaciones');
    }

    protected $rules = [
        "solicitante" => "required",
        "cedula" => "required",
        "celular" => "required",
    ];

    public function registrar()
    {
        $this->validate();
        DB::beginTransaction();
        try {
            $vigencia = Carbon::now()->addHours(24);
            $airbnblink = Airbnblink::create(
                [
                    "cliente_id" => $this->cliente_id,
                    "solicitante" => $this->solicitante,
                    "cedula" => $this->cedula,
                    "celular" => $this->celular,
                    "observaciones" => $this->observaciones,
                    "vigencia" => $vigencia,
                    "link"=>""
                ]
            );
            $encryptedId = Crypt::encrypt($airbnblink->id);
            $link = url('/') .'/formulario-airbnb'.'/'.$encryptedId;
            $airbnblink->link =$link;
            $airbnblink->save();

            $this->emit('success', $airbnblink->id);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            $this->emit('error', 'Ha ocurrido un error.');
        }
    }

    public function sendWhatsAppLink($id)
    {
        // Buscar el registro en el modelo Airbnblink
        $airbnblink = Airbnblink::find($id);
    
        // Verificar si el registro existe
        if (!$airbnblink) {
            return response()->json(['error' => 'Registro no encontrado'], 404);
        }
    
        // Obtener el link del registro
        $link = $airbnblink->link;
    
        // Definir el número de teléfono (puedes hacerlo dinámico)
        $telefono = '591'.$airbnblink->celular; // Reemplaza con el número al que deseas enviar el mensaje
    
        // Crear el enlace de WhatsApp
        $mensaje = urlencode("Hola, aquí tienes el enlace: $link");
        $whatsappUrl = "https://wa.me/$telefono?text=$mensaje";
    
        // Redirigir al enlace de WhatsApp
        // return redirect($whatsappUrl);
        $this->emit('open-whatsapp', ['url' => $whatsappUrl]);
    }

    public function exportarPDF($id)
    {
        $airbnbtraveler = Airbnbtraveler::find($id);
        $data = $airbnbtraveler->toArray();
        $companions_count = $airbnbtraveler->airbnbcompanions->count();
        $condominio=$airbnbtraveler->airbnblink->cliente->nombre;
        $data['condominio']=$condominio;
        $data['companions_count'] = $companions_count;
        $companions = array();
        if ($companions_count > 0) {
            foreach ($airbnbtraveler->airbnbcompanions as $airbnbcompanion) {
                $companions[] = $airbnbcompanion->toArray();
            }
        }
        $data['companions_data'] = $companions;

        $pdf = Pdf::loadView('pdfs.pdfformularioairbnb', $data);
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'formularioairbnb.pdf');
    }

    public function updatedCliente_id()
    {
        $this->resetPage();
    }
    public function updatedEstado()
    {
        $this->resetPage();
    }
    public function updatedInicio()
    {
        $this->resetPage();
    }
    public function updatedFinal()
    {
        $this->resetPage();
    }
    public function updatedSearch()
    {
        $this->resetPage();
    }
}
