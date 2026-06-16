<?php

namespace App\Http\Livewire\Admin;

use App\Models\Cliente;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Propietario;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ListadoPropietarios extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $propietario_id, $propietario, $cliente_id, $nombre, $cedula, $telefono, $email, $direccion, $ciudad, $activo = true;
    public $search = '', $perPage = 5, $perPageOptions = [5, 10, 15, 25, 50];
    public $modalMode = 'create'; // create | edit | show
    public $activoFiltro = "";

    protected function rules()
    {
        return [
            'nombre'    => 'required|string|max:100',
            'cedula'    => [
                'required',
                'string',
                'max:20',
                Rule::unique('propietarios', 'cedula')->ignore($this->propietario_id),
            ],
            'telefono'  => 'nullable|string|max:15',
            'email'     => 'nullable|email|max:100',
            'direccion' => 'nullable|string|max:255',
            'ciudad'    => 'nullable|string|max:100',
            'activo'    => 'boolean',
        ];
    }

    public function render()
    {
        $clientes = Cliente::all();
        $propietarios = Propietario
            ::where(function ($query) {
                $query->where('nombre', 'like', "%{$this->search}%")
                    ->orWhere('cedula', 'like', "%{$this->search}%");
            })
            ->when($this->activoFiltro !== "", function ($query) {
                $query->where('activo', $this->activoFiltro);
            })
            ->orderBy('id', 'desc')
            ->paginate($this->perPage);


        return view('livewire.admin.listado-propietarios', compact('propietarios', 'clientes'))->extends('adminlte::page');
    }

    public function edit($id)
    {
        $this->resetInput();
        $prop = Propietario::find($id);
        $this->propietario_id = $prop->id;
        $this->nombre = $prop->nombre;
        $this->cedula = $prop->cedula;
        $this->telefono = $prop->telefono;
        $this->email = $prop->email;
        $this->direccion = $prop->direccion;
        $this->ciudad = $prop->ciudad;
        $this->activo = $prop->activo;
        $this->cliente_id = $prop->cliente_id;
        $this->modalMode = 'edit';
        $this->emit('openModal');
    }
    public function show($id)
    {
        $this->resetInput();
        $prop = Propietario::find($id);
        $this->propietario_id = $prop->id;
        $this->nombre = $prop->nombre;
        $this->cedula = $prop->cedula;
        $this->telefono = $prop->telefono;
        $this->email = $prop->email;
        $this->direccion = $prop->direccion;
        $this->ciudad = $prop->ciudad;
        $this->activo = $prop->activo;
        $this->cliente_id = $prop->cliente_id;
        $this->modalMode = 'show';
        $this->emit('openModal');
    }

    public function create()
    {
        $this->modalMode = 'create';
        $this->resetInput();
        $this->emit('openModal');
    }

    public function resetInput()
    {
        $this->reset(['propietario_id', 'nombre', 'cedula', 'telefono', 'email', 'direccion', 'ciudad', 'activo']);
        $this->activo = true;
    }

    public function openModal($mode, $id = null)
    {
        $this->resetInput();
        $this->modalMode = $mode;

        if ($id) {
            $prop = Propietario::findOrFail($id);
            $this->propietario_id = $prop->id;
            $this->nombre = $prop->nombre;
            $this->cedula = $prop->cedula;
            $this->telefono = $prop->telefono;
            $this->email = $prop->email;
            $this->direccion = $prop->direccion;
            $this->ciudad = $prop->ciudad;
            $this->activo = $prop->activo;
            if ($mode == 'edit') {
                $this->rules['cedula'] = "required|string|max:20|unique:propietarios,cedula,{$this->propietario_id}";
            }
        }

        $this->emit('openModal');
    }

    public function save()
    {
        $this->validate();
        DB::beginTransaction();
        try {
            if ($this->modalMode === 'edit') {
                $propietario = Propietario::find($this->propietario_id);
                $propietario->nombre = $this->nombre;
                $propietario->cedula = $this->cedula;
                $propietario->telefono = $this->telefono;
                $propietario->email = $this->email;
                $propietario->direccion = $this->direccion;
                $propietario->ciudad = $this->ciudad;
                $propietario->activo = $this->activo;
                $propietario->cliente_id = $this->cliente_id;
                $propietario->save();

                if ($propietario->user) {
                    $propietario->user->status = $this->activo ? 1 : 0;
                    $propietario->user->save();
                } else {
                    $user = $propietario->user()->create([
                        "name" => $this->nombre,
                        "password" => \Illuminate\Support\Facades\Hash::make($this->cedula),
                        "email" => $this->email ?: strtolower(str_replace(" ", "", $this->email)),
                        "template" => "PROPIETARIO",
                        "status" => $this->activo ? 1 : 0,
                    ]);

                    $propietario->user_id = $user->id;
                    $propietario->save();
                }
            } else {
                $propietario = Propietario::create([
                    'nombre'    => $this->nombre,
                    'cedula'    => $this->cedula,
                    'telefono'  => $this->telefono,
                    'email'     => $this->email,
                    'direccion' => $this->direccion,
                    'ciudad'    => $this->ciudad,
                    'activo'    => $this->activo,
                    'cliente_id' => $this->cliente_id,
                ]);

                $user = $propietario->user()->create([
                    "name" => $this->nombre,
                    "password" => \Illuminate\Support\Facades\Hash::make($this->cedula),
                    "email" => $this->email ?: strtolower(str_replace(" ", "", $this->email)),
                    "template" => "PROPIETARIO",
                    "status" => $this->activo ? 1 : 0,
                ]);

                $propietario->user_id = $user->id;
                $propietario->save();
            }
            DB::commit();
            $this->emit('closeModal');
            $this->resetInput();
            $this->emit('success', 'Propietario guardado correctamente.');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->emit('error', $th->getMessage());
        }
    }

    protected $listeners = ['delete'];

    public function delete($id)
    {
        Propietario::findOrFail($id)->delete();
        $this->emit('success', 'Propietario eliminado correctamente.');
    }

    public function addResidencia($propietario_id)
    {
        $this->propietario = Propietario::find($propietario_id);
        $this->emit('openModalResidencia');
    }

    public $numeropuerta = '', $piso = '', $calle = '', $nrolote = '', $manzano = '', $notas = '';

    public function resetAll()
    {
        $this->reset('numeropuerta', 'piso', 'calle', 'nrolote', 'manzano', 'notas');
    }

    public function storeResidencia()
    {
        $this->validate([
            'numeropuerta' => 'nullable|string|max:255',
            'piso' => 'nullable|string|max:255',
            'calle' => 'nullable|string|max:255',
            'nrolote' => 'nullable|string|max:255',
            'manzano' => 'nullable|string|max:255',
        ]);
        if ($this->numeropuerta != "" || $this->calle != "" || $this->nrolote != "" || $this->manzano != "" || $this->piso != "") {
            DB::beginTransaction();
            try {
                DB::commit();
                $this->propietario->residencias()->create([
                    'numeropuerta' => $this->numeropuerta,
                    'piso' => $this->piso,
                    'calle' => $this->calle,
                    'nrolote' => $this->nrolote,
                    'notas' => $this->notas,
                    'estado' => 'VERIFICADO',
                    'cliente_id' => $this->propietario->cliente_id,
                ]);

                $email = "";
                if ($this->propietario->email == "" || is_null($this->propietario->email)) {
                    $email = strtolower($this->propietario->nombre . $this->propietario->id . "@" . config('app.name'));
                    $email = str_replace(" ", "", $email);
                    $this->propietario->email = $email;
                    $this->propietario->save();
                }

                if (is_null($this->propietario->user_id) || $this->propietario->user_id === "") {
                    $user = \App\Models\User::create([
                        "name" => $this->propietario->nombre,
                        "password" => \Illuminate\Support\Facades\Hash::make($this->propietario->cedula),
                        "email" => $this->propietario->email,
                        "template" => "PROPIETARIO",
                        "status" => 1,
                    ]);
                    $this->propietario->user_id = $user->id;
                    $this->propietario->save();
                }
            } catch (\Throwable $th) {
                DB::rollBack();
                $this->emit('error', 'Error al guardar la residencia: ' . $th->getMessage());
                return;
            }
            $this->resetAll();
            $this->propietario = Propietario::find($this->propietario->id);
            // $this->emit('closeModalResidencia');
            $this->emit('success', 'Residencia agregada correctamente.');
        } else {
            $this->emit('error', 'Debe completar al menos un campo para agregar una residencia.');
        }
    }
    public function updatedActivoFiltro()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }
    public function updatedPerPage()
    {
        $this->resetPage();
    }
}
