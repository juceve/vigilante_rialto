<?php

namespace App\Http\Livewire\Admin\Partials;

use App\Models\Cliente;
use App\Models\Empleado;
use Livewire\Component;
use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\TemplateProcessor;

class PtMemorandum extends Component
{

    public $m_cite = "", $selID = "", $m_fecha = "", $m_motivo = "";

    public $empleados = null, $empleado = null;

    public function mount(){
        $this->empleados = Empleado::all();
        $this->m_motivo= "Se llama severamente la atención a usted, por no venir a labores en fecha 08/07/14 demostrando en su posicion de una falta total de responsabilidad en sus funciones especificas, recordandole que se hara el descuento de acuerdo a normativa legal vigente estipulada en el ministerio de trabajo en sus haberes del mes de Julio del presente.";
    }

    public function updatedSelID(){
        $this->empleado = Cliente::find($this->selID);
    }

    public function render()
    {
        return view('livewire.admin.partials.pt-memorandum');
    }

    protected $rules = [
        'selID' => ' required',
    ];

    public function generarInforme()
    {

        $this->validate();
        try {
            $template = new TemplateProcessor("docs/pt_memorandum.docx");

            $template->setValue('cite', $this->m_cite);
            $template->setValue('fecha', fechaEs($this->m_fecha));
            $template->setValue('empleado', $this->empleado->nombres." ".$this->empleado->apellidos);
            
            $template->setValue('motivo', $this->m_motivo);

            $tempFile = tempnam(sys_get_temp_dir(), 'PHPWord');
            $template->saveAs($tempFile);

            $headers = [
                "Content-Type: application/octet-stream",
            ];

            return response()->download($tempFile, 'Memorandum.docx', $headers)->deleteFileAfterSend(true);
        } catch (Exception $e) {
            return back($e->getCode());
        }
    }
}
