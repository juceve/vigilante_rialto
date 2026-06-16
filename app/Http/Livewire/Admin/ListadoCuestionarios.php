<?php

namespace App\Http\Livewire\Admin;

use App\Models\ChklListaschequeo;
use App\Models\ChklPregunta;
use App\Models\Cliente;
use App\Models\Tipoboleta;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ListadoCuestionarios extends Component
{
    use WithPagination;
    protected $paginationTheme = "bootstrap";


    public $search = '', $filas = 10, $selCliente;
    public $cliente_id, $titulo, $descripcion, $activo = 1, $modalMode = 'Nuevo';
    public $preguntas = [], $pregunta = "", $tipoboleta_id = '', $requerida = 1;
    public $cuestionarioId, $preguntaId, $modePregunta = 'Nuevo';

    public function render()
    {
        $resultados = ChklListaschequeo::query()
        ->when($this->selCliente, function ($query) {
            $query->where('cliente_id', $this->selCliente);
        })
        ->paginate($this->filas);

        $clientes = Cliente::where('status', 1)->pluck('nombre', 'id');
        $tipoboletas = Tipoboleta::where('status', 1)->pluck('nombre', 'id');
        return view('livewire.admin.listado-cuestionarios', compact('resultados', 'clientes', 'tipoboletas'))->extends('adminlte::page');
    }

    public function resetAll()
    {
        $this->reset(['cliente_id', 'titulo', 'descripcion', 'activo', 'modalMode', 'preguntas', 'pregunta', 'tipoboleta_id', 'requerida', 'modePregunta']);
    }

    public function create()
    {
        $this->resetAll();
        $this->emit('openModalCuestionario');
    }

    public function agregarPregunta()
    {
        $boleta = '';
        if ($this->pregunta != '') {
            if ($this->tipoboleta_id) {
                $boleta = Tipoboleta::find($this->tipoboleta_id)->nombre;
            }
            $this->preguntas[] = [
                'id' => '',
                'pregunta' => $this->pregunta,
                'requerida' => $this->requerida,
                'tipoboleta_id' => $this->tipoboleta_id,
                'boleta' => $boleta,
            ];
            $this->reset('pregunta', 'tipoboleta_id', 'requerida');
        } else {
            $this->emit('toast-error', 'La pregunta no puede estar vacía');
        }
    }

    public function editarPregunta($index)
    {
        $this->preguntaId = $this->preguntas[$index]['id'];
        $this->pregunta = $this->preguntas[$index]['pregunta'];
        $this->requerida = $this->preguntas[$index]['requerida'];
        $this->tipoboleta_id = $this->preguntas[$index]['tipoboleta_id'];
        $this->modePregunta = 'Editar';
    }

    public function actualizarPregunta()
    {
        $boleta = '';
        if ($this->pregunta != '') {
            if ($this->tipoboleta_id) {
                $boleta = Tipoboleta::find($this->tipoboleta_id)->nombre;
            }
            foreach ($this->preguntas as $index => $item) {
                if ($item['id'] == $this->preguntaId) {
                    $this->preguntas[$index] = [
                        'id' => $this->preguntaId,
                        'pregunta' => $this->pregunta,
                        'requerida' => $this->requerida,
                        'tipoboleta_id' => $this->tipoboleta_id,
                        'boleta' => $boleta,
                    ];
                    break;
                }
            }
            $this->reset('pregunta', 'tipoboleta_id', 'requerida');
            $this->modePregunta = 'Nuevo';
        } else {
            $this->emit('toast-error', 'La pregunta no puede estar vacía');
        }
    }

    public function eliminarPregunta($index)
    {
        unset($this->preguntas[$index]);
        $this->preguntas = array_values($this->preguntas);
        $this->emit('toast-warning', 'La pregunta ha sido eliminada');
    }

    protected $listeners = ['store', 'update'];

    public function store()
    {
        
        $this->validate([
            'cliente_id' => 'required',
            'titulo' => 'required',
            'descripcion' => 'required',
            // 'preguntas' => 'required|array|min:1',
        ]);

        if (count($this->preguntas) == 0) {
            $this->emit('toast-error', 'Debe agregar al menos una pregunta al cuestionario');
            return;
        }

        DB::beginTransaction();
        try {
            if ($this->cliente_id === "0") {
                $tclientes = Cliente::where('status', 1)->get();
                foreach ($tclientes as $tcliente) {
                    $cuestionario = ChklListaschequeo::create([
                        'cliente_id' => $tcliente->id,
                        'titulo' => $this->titulo,
                        'descripcion' => $this->descripcion,
                    ]);
                    foreach ($this->preguntas as $item) {
                        $pregunta = $cuestionario->chklPreguntas()->create([
                            'descripcion' => $item['pregunta'],
                            'requerida' => $item['requerida'],
                        ]);
                        if ($item['tipoboleta_id']) {
                            $pregunta->tipoboleta_id = $item['tipoboleta_id'];
                            $pregunta->save();
                        }
                    }
                }
            } else {
                $cuestionario = ChklListaschequeo::create([
                    'cliente_id' => $this->cliente_id,
                    'titulo' => $this->titulo,
                    'descripcion' => $this->descripcion,
                ]);
                foreach ($this->preguntas as $item) {
                    $pregunta = $cuestionario->chklPreguntas()->create([
                        'descripcion' => $item['pregunta'],
                        'requerida' => $item['requerida'],
                    ]);
                    if ($item['tipoboleta_id']) {
                        $pregunta->tipoboleta_id = $item['tipoboleta_id'];
                        $pregunta->save();
                    }
                }

            }
            DB::commit();
            $this->emit('success', 'Cuestionario creado correctamente');
            $this->resetAll();
            $this->emit('closeModalCuestionario');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->emit('error', 'Error al crear el cuestionario');
        }
    }

    public function edit($id)
    {
        $this->resetAll();
        $this->cuestionarioId = $id;
        $cuestionario = ChklListaschequeo::find($id);
        $this->cliente_id = $cuestionario->cliente_id;
        $this->titulo = $cuestionario->titulo;
        $this->descripcion = $cuestionario->descripcion;
        $this->activo = $cuestionario->activo;
        foreach ($cuestionario->chklPreguntas as $pregunta) {
            $boleta = '';
            if ($pregunta->tipoboleta_id) {
                $boleta = Tipoboleta::find($pregunta->tipoboleta_id)->nombre;
            }
            $this->preguntas[] = [
                'id' => $pregunta->id,
                'pregunta' => $pregunta->descripcion,
                'requerida' => $pregunta->requerida,
                'tipoboleta_id' => $pregunta->tipoboleta_id,
                'boleta' => $boleta,
            ];
        }
        $this->modalMode = 'Editar';
        $this->emit('openModalCuestionario');
    }

    public function update()
    {
        $this->validate([
            'cliente_id' => 'required',
            'titulo' => 'required',
            'descripcion' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $cuestionario = ChklListaschequeo::find($this->cuestionarioId);
            $cuestionario->update([
                'cliente_id' => $this->cliente_id,
                'titulo' => $this->titulo,
                'descripcion' => $this->descripcion,
                'activo' => $this->activo,
            ]);

            foreach ($this->preguntas as $item) {
                if ($item['id']) {
                    $pregunta = ChklPregunta::find($item['id']);
                    $pregunta->descripcion = $item['pregunta'];
                    $pregunta->tipoboleta_id = $item['tipoboleta_id'] ? $item['tipoboleta_id'] : null;
                    $pregunta->requerida = $item['requerida'];
                    $pregunta->save();
                } else {
                    $pregunta = $cuestionario->chklPreguntas()->create([
                        'descripcion' => $item['pregunta'],
                        'requerida' => $item['requerida'],
                    ]);
                    if ($item['tipoboleta_id']) {
                        $pregunta->tipoboleta_id = $item['tipoboleta_id'];
                        $pregunta->save();
                    }
                }
            }

            DB::commit();
            $this->emit('success', 'Cuestionario actualizado correctamente');
            $this->resetAll();
            $this->emit('closeModalCuestionario');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->emit('error', 'Error al actualizar el cuestionario');
        }
    }

    public function updatedSelCliente(){
        $this->resetPage();
    }
    public function updatedFilas(){
        $this->resetPage();
    }
}
