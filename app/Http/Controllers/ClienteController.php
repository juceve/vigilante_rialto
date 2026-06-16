<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Oficina;
use App\Models\Tipodocumento;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class ClienteController
 * @package App\Http\Controllers
 */
class ClienteController extends Controller
{


    public function __construct()
    {
        $this->middleware('can:clientes.index')->only('index');
        $this->middleware('can:clientes.create')->only('create', 'store');
        $this->middleware('can:clientes.edit')->only('edit', 'update');
        $this->middleware('can:clientes.destroy')->only('destroy');
    }

    public function index()
    {

        return view('admin.cliente.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cliente = new Cliente();

        $tipodocs = Tipodocumento::all()->pluck('name', 'id');
        $oficinas = Oficina::all()->pluck('nombre', 'id');
        return view('admin.cliente.create', compact('cliente', 'tipodocs', 'oficinas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Cliente::$rules);
        DB::beginTransaction();
        try {

            $cliente = Cliente::create($request->all());

            // $usuario = User::create([
            //     "name" => $request->nombre,
            //     "email" => $request->email,
            //     "password" => bcrypt($request->nrodocumento),
            //     "template" => 'CLIENTE',
            //     "status" => true
            // ]);
            DB::commit();
            return redirect()->route('clientes.index')
                ->with('success', 'Cliente creado correctamente.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('clientes.create')
                ->with('success', 'Ha ocurrido un error.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cliente = Cliente::find($id);

        return view('admin.cliente.show', compact('cliente'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cliente = Cliente::find($id);
        $oficinas = Oficina::all()->pluck('nombre', 'id');
        $tipodocs = Tipodocumento::all()->pluck('name', 'id');
        return view('admin.cliente.edit', compact('cliente', 'oficinas', 'tipodocs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Cliente $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cliente $cliente)
    {
        request()->validate(Cliente::$rules);

        $cliente->update($request->all());

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente actualizado correctamente.');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $cliente = Cliente::find($id)->delete();

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente eliminado correctamente.');
    }
}
