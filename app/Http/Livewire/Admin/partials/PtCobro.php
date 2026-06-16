<?php

namespace App\Http\Livewire\Admin\partials;

use App\Models\Cliente;
use Livewire\Component;
use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\TemplateProcessor;

class PtCobro extends Component
{
    public function render()
    {
        return view('livewire.admin.partials.pt-cobro')->extends('adminlte::page');
    }

    public $clientes = null, $selID = "", $cliente = null;

    public $c_cite = "", $c_mescobro = "", $c_gestion = "", $c_fecha = "", $c_factura = "", $c_monto = "", $c_representante = "";

    public function mount()
    {
        $this->clientes = Cliente::all()->pluck('nombre', 'id');
    }


    public function updatedSelID()
    {
        $this->cliente = Cliente::find($this->selID);
        if ($this->cliente) {
            $this->c_representante = $this->cliente->personacontacto;
        } else {
            $this->c_representante = "";
        }
    }

    protected $rules = [
        'selID' => ' required',
        'c_mescobro' => ' required',
        'c_gestion' => ' required',
    ];

    public function generarInforme()
    {
        $this->validate();
        $ultimoDiaMes = ultDiaMes($this->c_gestion . "-" . $this->c_mescobro . "-01");
        try {
            $template = new TemplateProcessor("docs/pt_cobro.docx");

            $template->setValue('representante', $this->c_representante);
            $template->setValue('nrofactura', $this->c_factura);
            $template->setValue('fecha', fechaEs($this->c_fecha));
            $template->setValue('cliente', $this->cliente->nombre);
            $template->setValue('monto', $this->c_monto);
            $template->setValue('fechafinmes', $ultimoDiaMes);

            $tempFile = tempnam(sys_get_temp_dir(), 'PHPWord');
            $template->saveAs($tempFile);

            $headers = [
                "Content-Type: application/octet-stream",
            ];

            return response()->download($tempFile, 'Cobro.docx', $headers)->deleteFileAfterSend(true);
        } catch (Exception $e) {
            return back($e->getCode());
        }
    }
}
