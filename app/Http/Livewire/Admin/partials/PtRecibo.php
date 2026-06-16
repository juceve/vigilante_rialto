<?php

namespace App\Http\Livewire\Admin\partials;

use App\Models\Cliente;
use App\Models\ConversionNumeros;
use Livewire\Component;
use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\TemplateProcessor;

class PtRecibo extends Component
{
    public $clientes = null, $selID = "", $cliente = null;

    public $fecha = "", $mes = "", $monto = "";

    public function mount()
    {
        $this->clientes = Cliente::all()->pluck('nombre', 'id');
    }


    public function updatedSelID()
    {
        $this->cliente = Cliente::find($this->selID);
    }
    public function render()
    {
        return view('livewire.admin.partials.pt-recibo');
    }
    protected $rules = [
        'selID' => ' required',
        'monto' => ' required',
        'mes' => ' required',
    ];
    public function generar()
    {
        $this->validate();
        $conversiones = new ConversionNumeros();
        $literal = $conversiones->toInvoice($this->monto, 2, 'bolivianos');

        try {
            $template = new TemplateProcessor("docs/pt_recibo.docx");

            $template->setValue('cliente', $this->cliente->nombre);            
            $template->setValue('fecha', fechaEs($this->fecha));
            $template->setValue('monto', $this->monto);
            $template->setValue('mes', $this->mes);
            $template->setValue('montoliteral', $literal);


            $tempFile = tempnam(sys_get_temp_dir(), 'PHPWord');
            $template->saveAs($tempFile);

            $headers = [
                "Content-Type: application/octet-stream",
            ];

            return response()->download($tempFile, 'Recibo.docx', $headers)->deleteFileAfterSend(true);
        } catch (Exception $e) {
            return back($e->getCode());
        }
    }
}
