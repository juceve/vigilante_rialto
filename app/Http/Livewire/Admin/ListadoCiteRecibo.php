<?php

namespace App\Http\Livewire\Admin;


use App\Models\Citerecibo;
use App\Models\Cliente;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;

class ListadoCiteRecibo extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public  $selID = "", $cliente = null, $updCiteId;

    public  $representante;

    public $fecha = "", $mescobro = "", $gestioncobro = "", $monto = "";

    public $busqueda = "", $filas = 5, $gestion;

    public function render()
    {
        $clientes = Cliente::all()->pluck('nombre', 'id');

        $citerecibos = Citerecibo::where([['cite', 'like', "%$this->busqueda%"], ['gestion', $this->gestion]])
            ->orWhere([["cliente", "like", "%$this->busqueda%"], ['gestion', $this->gestion]])
            ->orWhere([["mescobro", "like", "%$this->busqueda%"], ['gestion', $this->gestion]])
            ->orderBy('correlativo', 'DESC')
            ->paginate($this->filas);

        return view('livewire.admin.listado-cite-recibo', compact('clientes', 'citerecibos'))->extends('adminlte::page');
    }

    public function mount()
    {
        $this->fecha = date('Y-m-d');
        $this->gestion = date('Y');
    }

    public function updatedBusqueda()
    {
        $this->resetPage();
    }
    public function updatedGestion()
    {
        $this->resetPage();
    }

    public function resetAll()
    {
        $this->reset(
            'selID',
            'cliente',
            'updCiteId',
            'mescobro',
            'gestioncobro',
            'fecha',
            'monto',
            'representante'
        );
    }

    protected $listeners = ['anular'];

    public function updatedSelID()
    {
        $this->cliente = Cliente::find($this->selID);
    }

    public function previa()
    {
        $data = [];
        $data[] = 0;

        $datos = '0^0|' . fechaEs($this->fecha) . '|' . $this->cliente->nombre .  '|' . $this->mescobro . '-' . $this->gestioncobro . '|' . $this->monto;

        $datos = codGet($datos);
        Session::put('data-citerecibo', $datos);

        $this->emit('renderizarpdf');
    }

    public function registrar()
    {
        DB::beginTransaction();
        try {
            $last = Citerecibo::where('gestion', date('Y'))->orderBy('correlativo', 'DESC')->first();

            if ($last) {
                $last = $last->correlativo;
                $last++;
            } else {
                $last = 1;
            }

            $citecobro = Citerecibo::create([
                'correlativo' => $last,
                'gestion' => date('Y'),
                'cite' => "REC-"   . str_pad($last, 3, "0", STR_PAD_LEFT) . "/" . date('Y'),
                'fecha' => $this->fecha,
                'fechaliteral' => fechaEs($this->fecha),
                'cliente' => $this->cliente->nombre,
                'cliente_id' => $this->cliente->id,
                'mescobro' => $this->mescobro . '-' . $this->gestioncobro,
                'monto' => $this->monto,

            ]);

            DB::commit();

            $this->resetAll();
            $datos = $citecobro->id;
            $this->emit('renderizarpdf', $datos."|1");
            $this->emit('success', 'Cobro registrado correctamente.');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->emit('error', $th->getMessage());
        }
    }

    public function editar($citerecibo_id)
    {

        $citerecibo = citerecibo::find($citerecibo_id);
        $datocobro = explode('-', $citerecibo->mescobro);

        $this->updCiteId = $citerecibo_id;
        // $this->cliente = $citerecibo->cliente_data;

        $this->mescobro = $datocobro[0];
        $this->gestioncobro = $datocobro[1];
        $this->fecha = $citerecibo->fecha;
        $this->monto = $citerecibo->monto;
        $this->cliente = $citerecibo->cliente_data;

        $this->selID = $citerecibo->cliente_id;
    }

    public function actualizar()
    {

        DB::beginTransaction();
        try {

            $citerecibo = Citerecibo::find($this->updCiteId)->update([

                'fecha' => $this->fecha,
                'fechaliteral' => fechaEs($this->fecha),
                'cliente' => $this->cliente->nombre,
                'cliente_id' => $this->cliente->id,
                'mescobro' => $this->mescobro . '-' . $this->gestioncobro,
                'monto' => $this->monto,
            ]);

            DB::commit();

            $this->resetAll();

            $this->emit('success', 'Recibo actualizado correctamente.');
        } catch (\Throwable $th) {

            DB::rollBack();
            $this->emit('error', $th->getMessage());
        }
    }

    public function anular($citerecibo_id)
    {
        DB::beginTransaction();
        try {
            $citerecibo = Citerecibo::find($citerecibo_id)->update([
                'estado' => false,
            ]);
            DB::commit();
            $this->emit('success', 'Cite anulado correctamente.');
        } catch (\Throwable $th) {

            DB::rollBack();
            $this->emit('error', $th->getMessage());
        }
    }
}
