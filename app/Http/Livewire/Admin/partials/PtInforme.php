<?php

namespace App\Http\Livewire\Admin\partials;

use App\Models\Citeinforme;
use App\Models\Cliente;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\TemplateProcessor;

class PtInforme extends Component
{

    public $clientes = null, $selID = "", $cliente = null;

    public $i_cite = "", $i_representante = "", $i_objeto = "", $i_fecha = "", $i_referencia = "", $i_causal = "", $causales = [];

    public function mount()
    {
        $this->clientes = Cliente::all()->pluck('nombre', 'id');
        $this->i_fecha = date('Y-m-d');
    }


    public function updatedSelID()
    {
        $this->cliente = Cliente::find($this->selID);
        if ($this->cliente) {
            $this->i_representante = $this->cliente->personacontacto;
        } else {
            $this->i_representante = "";
        }
    }
    public function render()
    {
        return view('livewire.admin.partials.pt-informe');
    }

    protected $rules = [
        'selID' => ' required',
    ];

    public function i_agregarCausal()
    {
        $this->causales[] = $this->i_causal;
        $this->i_causal = "";
    }

    public function delICausal($i)
    {
        unset($this->causales[$i]);
        $this->causales = array_values($this->causales);
    }

    public function generarInforme()
    {
        $this->validate();

        try {
            $template = new TemplateProcessor("docs/pt_informe.docx");

            $template->setValue('cite', $this->i_cite);
            $template->setValue('objeto', $this->i_objeto);
            $template->setValue('fecha', fechaEs($this->i_fecha));
            $template->setValue('cliente', $this->cliente->nombre);
            $template->setValue('representante', $this->i_representante);
            $template->setValue('referencia', $this->i_referencia);

            $replacements = array();
            foreach ($this->causales as $item) {
                $replacements[] = array('causal' => $item);
            }

            $template->cloneBlock('causales', 0, true, false, $replacements);

            $tempFile = tempnam(sys_get_temp_dir(), 'PHPWord');
            $template->saveAs($tempFile);

            $headers = [
                "Content-Type: application/octet-stream",
            ];

            return response()->download($tempFile, 'Informe.docx', $headers)->deleteFileAfterSend(true);
        } catch (Exception $e) {
            return back($e->getCode());
        }
    }

    public function previa()
    {
        $data = [];
        $data[] = 0;
        $data[] = $this->i_objeto;
        $data[] = fechaEs($this->i_fecha);
        $data[] = $this->cliente->nombre;
        $data[] = $this->i_representante;
        $data[] = $this->i_referencia;
        $puntos = implode("|", $this->causales);
        $datos = '0|' . $this->i_objeto . '|' . fechaEs($this->i_fecha) . '|' . $this->cliente->nombre . '|' . $this->i_representante . '|' . $this->i_referencia . '^';
        $datos .= $puntos;

        $this->emit('renderizarpdf', $datos);
    }



    public function registrar()
    {
        DB::beginTransaction();
        try {
            $last = Citeinforme::where('gestion', date('Y'))->orderBy('correlativo', 'DESC')->first();

            if ($last) {
                $last = $last->correlativo;
                $last++;
            } else {
                $last = 1;
            }
            $puntos = implode("|", $this->causales);
            $citeinforme = Citeinforme::create([
                'correlativo' => $last,
                'gestion' => date('Y'),
                'cite' => date('Y') . "/" . str_pad($last, 4, "0", STR_PAD_LEFT),
                'fecha' => $this->i_fecha,
                'fechaliteral' => fechaEs($this->i_fecha),
                'objeto' => $this->i_objeto,
                'cliente' => $this->cliente->nombre,
                'representante' => $this->i_representante,
                'referencia' => $this->i_referencia,
                'puntos' => $puntos,
            ]);

            DB::commit();

            $this->reset('selID', 'cliente', 'i_cite', 'i_representante', 'i_objeto', 'i_fecha', 'i_referencia', 'i_causal', 'causales');
            $datos = $citeinforme->id;
            $this->emit('renderizarpdf', $datos);
            $this->emit('success', 'Informe registrado correctamente.');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->emit('error', $th->getMessage());
        }
    }
}
