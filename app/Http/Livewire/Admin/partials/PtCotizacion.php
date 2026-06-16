<?php

namespace App\Http\Livewire\Admin\partials;

use App\Http\Controllers\NumLetraController;
use App\Models\ConversionNumeros;
use Livewire\Component;
use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\TemplateProcessor;

class PtCotizacion extends Component
{
    public $destinatario = "", $cargo = "", $fecha = "", $monto = "";

    public function render()
    {
        return view('livewire.admin.partials.pt-cotizacion');
    }

    protected $rules = [
        'monto' => ' required',
    ];

    public function generar()
    {
        $this->validate();
        $conversiones = new ConversionNumeros();
        $literal = $conversiones->toInvoice($this->monto, 2, 'bolivianos');

        try {
            $template = new TemplateProcessor("docs/pt_cotizacion.docx");

            $template->setValue('destinatario', $this->destinatario);
            $template->setValue('cargo', $this->cargo);
            $template->setValue('fecha', fechaEs($this->fecha));
            $template->setValue('monto', $this->monto);
            $template->setValue('montoliteral', $literal);


            $tempFile = tempnam(sys_get_temp_dir(), 'PHPWord');
            $template->saveAs($tempFile);

            $headers = [
                "Content-Type: application/octet-stream",
            ];

            return response()->download($tempFile, 'Cotizacion.docx', $headers)->deleteFileAfterSend(true);
        } catch (Exception $e) {
            return back($e->getCode());
        }
    }
}
