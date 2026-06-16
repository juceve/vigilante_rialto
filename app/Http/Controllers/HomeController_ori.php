<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Designacione;
use App\Models\Designacionsupervisor;
use App\Models\Marcacione;
use App\Models\Residencia;
use App\Models\Usercliente;
use App\Models\Vwdesignacione;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (Auth::user()->status == 0) {
            Auth::logout(); // Cerramos sesión
            return redirect()->route('login')->with('error', 'Tu cuenta está inactiva. Contacta al administrador.');
        }

        if (!hayRevisionHoy()) {
            procesosDiarios();
        }
        if (Auth::user()->template == "OPER") {
            $empleado_id = Auth::user()->empleados[0]->id;
            $designaciones = null;
            if ($empleado_id) {
                $designaciones = Designacione::where('fechaFin', '>=', date('Y-m-d'))
                    ->where('fechaInicio', '<=', date('Y-m-d'))
                    ->where('empleado_id', $empleado_id)
                    ->where('estado', 1)
                    ->orderBy('id', 'DESC')->first();
            }

            if ($designaciones) {
                Session::put('designacion-oper', $designaciones->id);
                if ($designaciones->turno) {
                    Session::put('cliente_id-oper', $designaciones->turno->cliente_id);
                }
            } else {
                Session::put('cliente_id-oper', null);
            }

            return view('operativo', compact('designaciones'));
        }

        if (Auth::user()->template == "SUPERVISOR") {
            $empleado_id = Auth::user()->empleados[0]->id;
            $designaciones = null;
            if ($empleado_id) {
                $designaciones = Designacionsupervisor::where('fechaInicio', '<=', date('Y-m-d'))
                    ->where('empleado_id', $empleado_id)
                    ->where('estado', 1)
                    ->orderBy('id', 'DESC')->first();
            }

            if ($designaciones) {
                Session::put('designacion-super', $designaciones->id);
                Session::put('clientes-super', $designaciones->designacionsupervisorclientes);
            } 


            return view('supervisor.home', compact('designaciones'));
        }

        if (Auth::user()->template == "ADMIN") {

            return view('admin.home');
        }


        if (Auth::user()->template == "CLIENTE") {
            $usuariocliente = Usercliente::where('user_id', Auth::user()->id)->first();
            $cliente = $usuariocliente->cliente;
            $hoy = date('Y-m-d');
            $designaciones = Vwdesignacione::where([
                ['cliente_id', $cliente->id],
                ['fechaInicio', '<=', $hoy],
                ['fechaFin', '>=', $hoy],
                ['estado', true],
            ])->get();
            Session::put('cliente_id-oper', $cliente->id);
            return view('customer.home', compact('cliente', 'designaciones'));
        }
        if (Auth::user()->template == "PROPIETARIO") {

            $paseingresos = \App\Models\Paseingreso::where('estado', 1)
                ->whereHas('residencia', function ($query) {
                    $query->where('estado', 'VERIFICADO')
                        ->where('propietario_id', auth()->user()->propietario->id);
                })
                ->whereDate('fecha_inicio', '<=', Carbon::today())
                ->whereDate('fecha_fin', '>=', Carbon::today())
                ->get();

            $residencias = Residencia::where('propietario_id', auth()->user()->propietario->id)
                ->where('estado', 'VERIFICADO')->get();

            return view('propietario.home', compact('paseingresos', 'residencias'));
        }
    }
}
