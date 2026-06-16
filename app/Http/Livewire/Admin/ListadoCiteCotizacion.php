<?php

namespace App\Http\Livewire\Admin;

use App\Models\Citecotizacion;
use App\Models\Detallecotizacione;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;

class ListadoCiteCotizacion extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $destinatario = "", $cargo = "", $monto = "0", $fecha = "", $updCiteId = "";

    public $detalle = '', $cantidad = 1, $precio = 0, $detalles = [];

    public $busqueda = "", $filas = 5, $gestion;
    public $procesando = false;

    public function render()
    {
        $citecotizacions = Citecotizacion::where([['cite', 'like', "%$this->busqueda%"], ['gestion', $this->gestion]])
            ->orWhere([["destinatario", "like", "%$this->busqueda%"], ['gestion', $this->gestion]])
            ->orWhere([["fecha", "like", "%$this->busqueda%"], ['gestion', $this->gestion]])
            ->orderBy('correlativo', 'DESC')
            ->paginate($this->filas);
        return view('livewire.admin.listado-cite-cotizacion', compact('citecotizacions'))->extends('adminlte::page');
    }

    protected $listeners = ['anular'];

    public function addDetalle()
    {

        $this->validate([
            'detalle' => 'required',
            'cantidad' => 'required',
            'precio' => 'required',
        ]);
        $this->procesando = true;
        $this->detalles[] = array($this->detalle, $this->cantidad, $this->precio);
        $this->monto += ($this->precio * $this->cantidad);
        $this->reset('detalle', 'cantidad', 'precio');
        $this->procesando = false;
        $this->emit('toast-success', 'Detalle agregado!');
    }

    public function delDetalle($i)
    {
        $this->monto -= ($this->detalles[$i][1] * $this->detalles[$i][2]);
        unset($this->detalles[$i]);
        $this->detalles = array_values($this->detalles);
        $this->emit('toast-warning', 'Detalle eliminado!');
    }

    public function mount()
    {
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
            'fecha',
            'destinatario',
            'cargo',
            'monto',
            'detalle',
            'cantidad',
            'precio',
            'detalles'
        );
    }

    public function previa()
    {
        $data = [];
        $valido_hasta = date('Y-m-d', strtotime($this->fecha . ' +10 day'));
        foreach ($this->detalles as $item) {
            $data[] = array('detalle' => $item[0], 'cantidad' => $item[1], 'precio' => $item[2]);
        }
        $datos = '0^0|' . fechaEs($this->fecha) . '|' . $this->destinatario . '|' .  $this->cargo . '|' . $this->monto . '|' . fechaEs($valido_hasta);

        $datos = codGet($datos);
        Session::put('data-citecotizacion', $datos);
        Session::put('data-detalles', $data);

        $this->emit('renderizarpdf');
    }

    public function registrar()
    {
        DB::beginTransaction();
        try {
            $last = Citecotizacion::where('gestion', date('Y'))->orderBy('correlativo', 'DESC')->first();

            if ($last) {
                $last = $last->correlativo;
                $last++;
            } else {
                $last = 1;
            }

            $citecotizacion = Citecotizacion::create([
                'correlativo' => $last,
                'gestion' => date('Y'),
                'cite' => "COT-"   . str_pad($last, 3, "0", STR_PAD_LEFT) . "/" . date('Y'),
                'fecha' => $this->fecha,
                'fechaliteral' => fechaEs($this->fecha),
                'destinatario' => $this->destinatario,
                'cargo' => $this->cargo,
                'monto' => $this->monto,

            ]);

            foreach ($this->detalles as $detalle) {
                $detallecotizacion = Detallecotizacione::create([
                    'citecotizacion_id' => $citecotizacion->id,
                    'detalle' => $detalle[0],
                    'cantidad' => $detalle[1],
                    'precio' => $detalle[2],
                ]);
            }

            DB::commit();

            $this->resetAll();

            $datos = $citecotizacion->id;
            $this->emit('renderizarpdf', $datos . "|1");
            $this->emit('success', 'Cotización registrado correctamente.');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->emit('error', $th->getMessage());
        }
    }

    public function editar($citecotizacion_id)
    {

        $citecotizacion = Citecotizacion::find($citecotizacion_id);

        $this->updCiteId = $citecotizacion_id;
        $this->destinatario = $citecotizacion->destinatario;
        $this->cargo = $citecotizacion->cargo;
        $this->fecha = $citecotizacion->fecha;
        $detalles = [];
        $total = 0;
        foreach ($citecotizacion->detalles as $detalle) {
            $detalles[] = array($detalle->detalle, $detalle->cantidad, $detalle->precio);
            $total += ($detalle->cantidad * $detalle->precio);
        }
        $this->monto = $total;
        $this->detalles = $detalles;
    }

    public function actualizar()
    {
        DB::beginTransaction();
        try {

            $citecotizacion = Citecotizacion::find($this->updCiteId);
            foreach ($citecotizacion->detalles as $detalle) {
                $detalle->delete();
            }
            $citecotizacion->update([

                'fecha' => $this->fecha,
                'fechaliteral' => fechaEs($this->fecha),
                'destinatario' => $this->destinatario,
                'cargo' => $this->cargo,
                'monto' => $this->monto,

            ]);

            foreach ($this->detalles as $detalle) {
                $detallecotizacion = Detallecotizacione::create([
                    'citecotizacion_id' => $citecotizacion->id,
                    'detalle' => $detalle[0],
                    'cantidad' => $detalle[1],
                    'precio' => $detalle[2],
                ]);
            }

            DB::commit();

            $this->resetAll();

            $this->emit('success', 'Cotización actualizado correctamente.');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->emit('error', $th->getMessage());
        }
    }

    public function anular($citecotizacion_id)
    {
        DB::beginTransaction();
        try {
            $citecotizacion = Citecotizacion::find($citecotizacion_id)->update([
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
