<?php

use App\Http\Controllers\AreaController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\CitecobroController;
use App\Http\Controllers\CitecotizacionController;
use App\Http\Controllers\CiteinformeController;
use App\Http\Controllers\CitememorandumController;
use App\Http\Controllers\CitereciboController;
use App\Http\Controllers\CldotacionController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DesignacioneController;
use App\Http\Controllers\DialibreController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\FormularioAirbnbController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MotivoController;
use App\Http\Controllers\NovedadeController;
use App\Http\Controllers\OficinaController;
use App\Http\Controllers\PaseingresoController;
use App\Http\Controllers\PropietarioController;
use App\Http\Controllers\RecorridoRondaController;
use App\Http\Controllers\RegistroguardiaController;
use App\Http\Controllers\RegrondaController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RrhhadelantoController;
use App\Http\Controllers\RrhhasistenciaController;
use App\Http\Controllers\RrhhbonoController;
use App\Http\Controllers\RrhhcargoController;
use App\Http\Controllers\RrhhdescuentoController;
use App\Http\Controllers\RrhhdotacionController;
use App\Http\Controllers\RrhhestadoController;
use App\Http\Controllers\RrhhestadodotacionController;
use App\Http\Controllers\RrhhKardexController;
use App\Http\Controllers\RrhhpermisoController;
use App\Http\Controllers\RrhhtipobonoController;
use App\Http\Controllers\RrhhtipocontratoController;
use App\Http\Controllers\RrhhtipodescuentoController;
use App\Http\Controllers\RrhhtipopermisoController;
use App\Http\Controllers\SistemaparametroController;
use App\Http\Controllers\SueldoController;
use App\Http\Controllers\TareaController;
use App\Http\Controllers\UbicacionController;
use App\Http\Controllers\UploadsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VigilanciaController;
use App\Http\Controllers\VisitaController;

use App\Http\Livewire\Admin\Admrondas;
use App\Http\Livewire\Admin\Aprobaciones;
use App\Http\Livewire\Admin\ClienteDotaciones;
use App\Http\Livewire\Admin\CronogramaMensualEmpleados;
use App\Http\Livewire\Admin\CtrlAllAirbnb;
use App\Http\Livewire\Admin\Diaslibres;
use App\Http\Livewire\Admin\Flujopases;
use App\Http\Livewire\Admin\GenDocs;
use App\Http\Livewire\Admin\ListadoCiteCobro;
use App\Http\Livewire\Admin\ListadoCiteCotizacion;
use App\Http\Livewire\Admin\ListadoCiteInforme;
use App\Http\Livewire\Admin\ListadoCiteMemorandum;
use App\Http\Livewire\Admin\ListadoCiteRecibo;
use App\Http\Livewire\Admin\ListadoHv;
use App\Http\Livewire\Admin\ListadoPropietarios;
use App\Http\Livewire\Admin\ListadoResidencias;
use App\Http\Livewire\Admin\ListadoRondas;
use App\Http\Livewire\Admin\ListadoSolicitudes;
use App\Http\Livewire\Admin\ManageFeriados;
use App\Http\Livewire\Admin\ManageSueldos;
use App\Http\Livewire\Admin\Nuevoptctrl;
use App\Http\Livewire\Admin\ProcesarSueldo;
use App\Http\Livewire\Admin\PuntosControl;
use App\Http\Livewire\Admin\PuntosControlV2;
use App\Http\Livewire\Admin\Regactividad;
use App\Http\Livewire\Admin\Registroasistencias;
use App\Http\Livewire\Admin\RegistroPropietario;
use App\Http\Livewire\Admin\Registroshv;
use App\Http\Livewire\Admin\Registrosnovedades;
use App\Http\Livewire\Admin\Registrosronda;
use App\Http\Livewire\Admin\Registrostareas;
use App\Http\Livewire\Admin\Registrosvisita;
use App\Http\Livewire\Admin\TurnoCliente;
use App\Http\Livewire\Admin\RegNovedades;
use App\Http\Livewire\Admin\ResumenOpercional;
use App\Http\Livewire\Admin\RondaPuntos;
use App\Http\Livewire\Admin\Usuariocliente;
use App\Http\Livewire\CtrlAsisitencias;
use App\Http\Livewire\Customer\Aprobaciones as CustomerAprobaciones;
use App\Http\Livewire\Customer\Cobros;
use App\Http\Livewire\Customer\Informes;
use App\Http\Livewire\Customer\Links;
use App\Http\Livewire\Customer\ListadoPropietarios as CustomerListadoPropietarios;
use App\Http\Livewire\Customer\ListadoResidencias as CustomerListadoResidencias;
use App\Http\Livewire\Customer\ListadoSolicitudes as CustomerListadoSolicitudes;
use App\Http\Livewire\Customer\Novedades as CustomerNovedades;
use App\Http\Livewire\Customer\Recibos;
use App\Http\Livewire\Customer\Rondas;
use App\Http\Livewire\Customer\Visitas;
use App\Http\Livewire\Propietarios\MisResidencias;
use App\Http\Livewire\Propietarios\Pases;
use App\Http\Livewire\Supervisores\InfoCuestionario;
use App\Http\Livewire\Vigilancia\Activacubrerelevos;
use App\Http\Livewire\Vigilancia\Checkairbnb;
use App\Http\Livewire\Vigilancia\ControlFlujos;
use App\Http\Livewire\Vigilancia\ControlPases;
use App\Http\Livewire\Vigilancia\DetallePase;
use App\Http\Livewire\Vigilancia\HombreVivo;
use App\Http\Livewire\Vigilancia\Novedades;
use App\Http\Livewire\Vigilancia\Panelvisitas;
use App\Http\Livewire\Vigilancia\Panico;
use App\Http\Livewire\Vigilancia\RecorridoRonda;
use App\Http\Livewire\Vigilancia\RegIngreso;
use App\Http\Livewire\Vigilancia\RegSalida;
use App\Http\Livewire\Vigilancia\Ronda;
use App\Http\Livewire\Vigilancia\SalidaVisita;
use App\Http\Livewire\Vigilancia\Vacaciones;
use App\Http\Livewire\Vigilancia\Vtareas;

use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\RrhhcontratoController;
use App\Http\Controllers\TipoboletaController;
use App\Http\Livewire\Admin\CajaChica;
use App\Http\Livewire\Admin\ListadoCuestionarios;
use App\Http\Livewire\Admin\ListadoDesignacionesSupervisores;
use App\Http\Livewire\Admin\ManageTurnoguardias;
use App\Http\Livewire\Admin\RegistrosSanciones;
use App\Http\Livewire\Supervisores\Novedades as SupervisoresNovedades;
use App\Http\Livewire\Supervisores\Panel;
use App\Http\Livewire\Vigilancia\Adelantos;
use App\Http\Livewire\Vigilancia\Asistencias;
use App\Http\Livewire\Vigilancia\Boletas;
use App\Models\Empleado;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});



Auth::routes([
    "register" => false,
    "reset" => false,
    "confirm" => false,
]);

Route::middleware(['auth'])->group(function () {

    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::get('rrhh/home', function () {
        return view('rrhh.home');
    })->name('rrhh.home');

    Route::get('users/asignaRol/{user}', [UserController::class, 'asinaRol'])->name('users.asignaRol');
    Route::put('users/updateRol/{user}', [UserController::class, 'updateRol'])->name('users.updateRol');
    Route::get('users/cambiaestado/{user}', [UserController::class, 'cambiaestado'])->name('users.cambiaestado');
    Route::resource('admin/roles', RoleController::class)->names('admin.roles');
    // Route::get('/home/marcacion/{id}', [HomeController::class, 'marcar'])->name('marcacion');
    Route::get('admin/profile', [UserController::class, 'profile'])->name('profile');
    Route::put('admin/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::put('admin/profile/password', [UserController::class, 'updatePassword'])->name('profile.password');
    Route::post('admin/profile/avatar', [UserController::class, 'updateAvatar'])->name('profile.avatar');
    Route::get('admin/users', [UserController::class, 'index'])->name('users');

    Route::get('vigilancia/panico', Panico::class)->name('vigilancia.panico');
    Route::get('vigilancia/cubrerelevos', Activacubrerelevos::class)->name('vigilancia.cubrerelevos');
    Route::get('vigilancia/ronda', Ronda::class)->name('vigilancia.ronda');
    Route::get('vigilancia/hombre-vivo/{intervalo}', HombreVivo::class)->name('vigilancia.hombre-vivo');
    Route::get('vigilancia/novedades/{designacion}', Novedades::class)->name('vigilancia.novedades');
    Route::get('vigilancia/panelvisitas/{designacion}', Panelvisitas::class)->name('vigilancia.panelvisitas');
    Route::get('vigilancia/visitas/reg-ingreso/{designacion}', RegIngreso::class)->name('vigilancia.regingreso');
    Route::get('vigilancia/visitas/reg-salida/{designacion}', RegSalida::class)->name('vigilancia.regsalida');
    Route::get('vigilancia/salidavisita/{visita_id}', SalidaVisita::class)->name('salidavisita');
    Route::get('vigilancia/tareas/{designacion}', Vtareas::class)->name('vigilancia.tareas');
    Route::get('vigilancia/airbnb/{designacione_id}', Checkairbnb::class)->name('vigilancia.airbnb');
    Route::get('vigilancia/control-pases/{designacione_id}', ControlPases::class)->name('vigilancia.controlpases');
    Route::get('vigilancia/detalle-pase/{designacione_id}/{pase_id}', DetallePase::class)->name('vigilancia.detallepase');
    Route::get('vigilancia/recorrido-ronda/{rondaejecutada_id}', RecorridoRonda::class)->name('vigilancia.recorrido_ronda');
    Route::get('vigilancia/controlairbnb', function () {
        return view('vigilancia.listadoairbnb');
    })->name('vigilancia.ctrlairbnb');
    Route::get('vigilancia/mi-perfil', [VigilanciaController::class, 'profile'])->name('vigilancia.profile');
    Route::get('vigilancia/vacaciones', Vacaciones::class)->name('vigilancia.vacaciones');
    Route::get('vigilancia/adelantos', Adelantos::class)->name('vigilancia.adelantos');
    Route::get('vigilancia/asistencias', Asistencias::class)->name('vigilancia.asistencias');
    Route::get('vigilancia/boletas', Boletas::class)->name('vigilancia.boletas');

    Route::get('supervisores/panel/{inspeccion_id}', Panel::class)->name('supervisores.panel');
    Route::get('supervisores/ejecutar-cuestionario/{cuestionario_id}/{inspeccion_id}', App\Http\Livewire\Supervisores\EjecutarCuestionario::class)->name('supervisores.ejecutarcuestionario');
    Route::get('supervisores/listado-supervisiones/{inspeccion_id}', App\Http\Livewire\Supervisores\ListadoSupervisiones::class)->name('supervisores.listadosupervisiones');
    Route::get('supervisores/listado-boletas/{inspeccion_id}', App\Http\Livewire\Supervisores\ListadoBoletas::class)->name('supervisores.listadoboletas');
    Route::get('supervisores/ctrl-asistencia/{inspeccion_id}', App\Http\Livewire\Supervisores\CtrlAsistencia::class)->name('supervisores.ctrlasistencia');
    Route::get('supervisores/emitir-boleta/{inspeccion_id}', App\Http\Livewire\Supervisores\EmitirBoleta::class)->name('supervisores.emitirboleta');
    Route::get('supervisores/info-cuestionario/{ejecucion_id}/{inspeccion_id}', InfoCuestionario::class)->name('supervisores.infocuestionario');
    Route::get('supervisores/dias-libres/{inspeccion_id}', \App\Http\Livewire\Supervisores\DiasLibres::class)->name('supervisores.diaslibres');
    Route::get('supervisores/ctrl-rondas/{inspeccion_id}', \App\Http\Livewire\Supervisores\CtrlRondas::class)->name('supervisores.ctrlrondas');
    Route::get('supervisores/cajachica/', \App\Http\Livewire\Supervisores\Cajachica::class)->name('supervisores.cajachica');
    Route::get('supervisores/novedades/{inspeccion_id}', \App\Http\Livewire\Supervisores\Novedades::class)->name('supervisores.novedades');


    Route::get('admin/sanciones', RegistrosSanciones::class)->middleware('can:admin.registros.descuentos')->name('admin.sanciones');
    Route::get('admin/visitas', Registrosvisita::class)->middleware('can:admin.registros.visitas')->name('admin.visitas');
    Route::get('admin/rondas', Registrosronda::class)->middleware('can:admin.registros.rondas')->name('admin.rondas');
    Route::get('admin/hombre-vivo', ListadoHv::class)->middleware('can:admin.hombre_vivo')->name('admin.hombre_vivo');
    Route::get('admin/recorrido-ronda/{rondaejecutada_id}', [RecorridoRondaController::class, 'recorrido'])->name('admin.recorrido_ronda');
    Route::get('admin/novedades', Registrosnovedades::class)->middleware('can:admin.registros.novedades')->name('admin.novedades');
    Route::get('admin/asistencias', Registroasistencias::class)->name('admin.asistencias');
    Route::get('admin/sueldos', ManageSueldos::class)->middleware('can:rrhhsueldos.index')->name('admin.sueldos');
    Route::get('admin/{rrhhsueldo_id}/procesar-sueldos', ProcesarSueldo::class)->middleware('can:rrhhsueldos.create')->name('admin.procesarsueldos');
    Route::get('admin/{cliente_id}/residencias', ListadoResidencias::class)->middleware('can:residencias.index')->name('admin.residencias');
    Route::get('admin/{cliente_id}/listado-solicitudes', ListadoSolicitudes::class)->middleware('can:residencias.solicitudes')->name('admin.listadosolicitudes');
    Route::get('admin/{propietario_id}/{cliente_id}/aprobacion-solicitudes', Aprobaciones::class)->middleware('can:residencias.aprobaciones')->name('admin.aprobacionsolicitudes');
    Route::get('admin/listado-propietarios', ListadoPropietarios::class)->middleware('can:propietarios.index')->name('admin.listadopropietarios');
    Route::get('admin/clientes/{cliente_id}/dotaciones', ClienteDotaciones::class)->middleware('can:admin.clientes.dotaciones.index')->name('admin.clientes.dotaciones');

    Route::post('/designaciones-historial/exportar', [DesignacioneController::class, 'exportar'])->name('designaciones-historial.exportar');

    Route::post('bonos/store', [RrhhbonoController::class, 'store'])->name('bonos.store');
    Route::post('descuentos/store', [RrhhdescuentoController::class, 'store'])->name('descuentos.store');
    Route::post('dotaciones/store', [RrhhdotacionController::class, 'store'])->name('dotaciones.store');
    Route::post('adelantos/store', [RrhhadelantoController::class, 'store'])->name('adelantos.store');
    Route::post('permisos/store', [RrhhpermisoController::class, 'store'])->name('permisos.store');
    Route::post('dotaciones/edit', [RrhhdotacionController::class, 'edit'])->name('dotaciones.edit');
    Route::post('bonos/edit', [RrhhbonoController::class, 'edit'])->name('bonos.edit');
    Route::post('descuentos/edit', [RrhhdescuentoController::class, 'edit'])->name('descuentos.edit');
    Route::post('adelantos/edit', [RrhhadelantoController::class, 'edit'])->name('adelantos.edit');
    Route::post('permisos/edit', [RrhhpermisoController::class, 'edit'])->name('permisos.edit');
    Route::post('dotaciones/update', [RrhhdotacionController::class, 'update'])->name('dotaciones.update');
    Route::post('bonos/update', [RrhhbonoController::class, 'update'])->name('bonos.update');
    Route::post('descuentos/update', [RrhhdescuentoController::class, 'update'])->name('descuentos.update');
    Route::post('adelantos/update', [RrhhadelantoController::class, 'update'])->name('adelantos.update');
    Route::post('permisos/update', [RrhhpermisoController::class, 'update'])->name('permisos.update');
    Route::post('asistencias/guardar', [RrhhasistenciaController::class, 'guardar'])->name('asistencias.guardar');
    Route::get('vigilancia/control-flujo/{designacione_id}', ControlFlujos::class)->name('vigilancia.flujopases');

    Route::get('admin/rrhh/asistencias/reporte', [RrhhasistenciaController::class, 'reporteAjax'])->name('asistencias.data');
    Route::get('dotaciones/data/{contrato_id}', [RrhhdotacionController::class, 'data'])->name('dotaciones.data');
    Route::get('descuentos/data/{contrato_id}/{mes?}/{anio?}', [RrhhdescuentoController::class, 'data'])->name('descuentos.data');
    Route::get('bonos/data/{contrato_id}', [RrhhbonoController::class, 'data'])->name('bonos.data');
    Route::get('adelantos/data/{contrato_id}/{mes?}/{anio?}', [RrhhadelantoController::class, 'data'])->name('adelantos.data');
    Route::get('permisos/data/{contrato_id}', [RrhhpermisoController::class, 'data'])->name('permisos.data');
    Route::get('admin/rrhh/kardex/{empleado_id}', [RrhhKardexController::class, 'kardex'])->name('rrhh.kardex');
    Route::get('admin/rrhh/ctrl-asistencias', CtrlAsisitencias::class)->middleware('can:rrhhctrlasistencias')->name('rrhhctrlasistencias');
    Route::get('admin/registro-actividad/{cliente_id?}', Regactividad::class)->middleware('can:admin.registros.panico')->name('admin.regactividad');
    Route::get('admin/turnos-cliente/{cliente_id}', TurnoCliente::class)->middleware('can:turnos.index')->name('admin.turnos-cliente');
    Route::get('admin/puntos-control/{turno_id}', PuntosControl::class)->name(('puntoscontrol'));
    Route::get('admin/feriados', ManageFeriados::class)->name(('feriados'));
    Route::get('admin/flujo-pases', Flujopases::class)->name('admin.flujopases');
    Route::get('admin/cronograma-dias-libres', CronogramaMensualEmpleados::class)->middleware('can:admin.cronogramadiaslibres')->name('admin.cronogramadiaslibres');
    Route::get('admin/resumen-operacional', ResumenOpercional::class)->middleware('can:admin.resumenoperacional')->name('admin.resumenoperacional');
    Route::get('admin/supervisores/turnos', ManageTurnoguardias::class)->name('admin.turnosupervisores');
    Route::get('admin/designaciones/supervisores/', ListadoDesignacionesSupervisores::class)->name('admin.designacionessupervisores');
    Route::get('admin/listado-cuestionarios', ListadoCuestionarios::class)->name('admin.listadocuestionarios');

    Route::get('/admin/puntos-control-v2/{turnoId}', PuntosControlV2::class)->name('puntoscontrolv2');

    Route::get('admin/clientes/{id}/rondas', ListadoRondas::class)->name('clientes.rondas');
    Route::get('admin/clientes/rondas/{ronda_id}/puntos', RondaPuntos::class)->name('clientes.ronda_puntos');
    Route::get('admin/control-rondas', Admrondas::class)->name('control.rondas');
    Route::get('admin/designaciones/pdfRondas/{id}', [DesignacioneController::class, 'pdfRondas'])->name('admin.designaciones.pdfRondas');
    Route::get('admin/designaciones/pdfNovedades/{id}', [DesignacioneController::class, 'pdfNovedades'])->name('pdfNovedades');
    Route::get('admin/designaciones/diaslibres/{id}', Diaslibres::class)->middleware('can:admin.registros.diaslibres')->name('designaciones.diaslibres');
    Route::get('admin/marcaciones/{id}', [DesignacioneController::class, 'marcaciones'])->name('marcaciones');
    Route::get('admin/pdfMarcaciones/{id}', [DesignacioneController::class, 'pdfMarcaciones'])->name('marcaciones.pdf');
    Route::get('admin/ubicacion/{lat}/{lng}', [UbicacionController::class, 'index'])->name('ubicacion');
    Route::get('admin/registroshv/{id}', Registroshv::class)->middleware('can:admin.registros.hombrevivo')->name('registroshv');
    Route::get('admin/reg-novedades/{id}', RegNovedades::class)->middleware('can:admin.registros.novedades')->name('regnovedades');
    Route::get('admin/gen-docs', GenDocs::class)->name('gendocs');
    Route::get('admin/tareas', Registrostareas::class)->middleware('can:tareas.index')->name('admin.tareas');
    Route::get('admin/ctrl-airbnb', CtrlAllAirbnb::class)->name('admin.ctrlallairbnb');
    Route::get('admin/designaciones/guardias', [DesignacioneController::class, 'designacioneguardia'])->name('admin.designacione-guardias');
    Route::get('admin/designaciones/selEmpleado/{empleado_id}', [DesignacioneController::class, 'seleccionarEmpleado'])->name('admin.selempleado');
    Route::get('admin/caja-chica', CajaChica::class)->name('admin.cajachica');


    Route::resource('registroguardias', RegistroguardiaController::class)->names('registroguardias');
    Route::resource('admin/empleados', EmpleadoController::class)->names('empleados');
    Route::resource('admin/areas', AreaController::class)->names('areas');
    Route::resource('admin/oficinas', OficinaController::class)->names('oficinas');
    Route::resource('admin/clientes', ClienteController::class)->names('clientes');
    Route::resource('admin/designaciones', DesignacioneController::class)->names('designaciones');
    Route::resource('admin/motivo-visita', MotivoController::class)->names('motivos');
    Route::resource('admin/rrhh/estados', RrhhestadoController::class)->names('rrhhestados');
    Route::resource('admin/rrhh/tipo-contratos', RrhhtipocontratoController::class)->names('rrhhtipocontratos');
    Route::resource('admin/rrhh/tipo-permisos', RrhhtipopermisoController::class)->names('rrhhtipopermisos');
    Route::resource('admin/rrhh/cargos', RrhhcargoController::class)->names('rrhhcargos');
    Route::resource('admin/rrhh/tipo-bonos', RrhhtipobonoController::class)->names('rrhhtipobonos');
    Route::resource('admin/rrhh/tipo-descuentos', RrhhtipodescuentoController::class)->names('rrhhtipodescuentos');
    Route::resource('admin/rrhh/estado-dotaciones', RrhhestadodotacionController::class)->names('rrhhestadodotacions');
    Route::resource('admin/parametros-generales', SistemaparametroController::class)->only(['edit', 'update', 'index'])->names('sistemaparametros');
    Route::resource('admin/tipo-boletas', TipoboletaController::class)->names('tipoboletas');
    Route::post('trae-tipodescuento', [RrhhtipodescuentoController::class, 'traeTipodescuento'])->name('traetipodescuento');
    // Route::resource('admin/tareas', TareaController::class)->names('tareas');

    Route::get('/ubicacion/{lat}/{lng}', function (string $lat, string $lng) {
        return view('admin.ubicacion', compact('lat', 'lng'));
    })->name('ubicacion');

    Route::get('nuevoptctrl/{cliente_id}', Nuevoptctrl::class)->name('nuevoptctrl');

    Route::get('pdf/contrato-vigente/{fecha}/{referencia}', [RrhhcontratoController::class, 'contratoPdf'])->name('pdf.contrato-vigente');
    Route::get('pdf/kardex-empleado/{empleadoID}', [EmpleadoController::class, 'pdfKardex'])->name('pdf.kardex');
    Route::get('pdf/visitas/', [VisitaController::class, 'pdfVisitas'])->name('pdf.visitas');
    Route::get('pdf/rondas/', [RegrondaController::class, 'pdfRondas'])->name('pdf.rondas');
    Route::get('pdf/rondasejecutadas/', [RegrondaController::class, 'rondasEjecutadas'])->name('pdf.rondasejecutadas');
    Route::get('pdf/novedades/', [NovedadeController::class, 'pdfNovedades'])->name('pdf.novedades');
    Route::get('pdf/tareas/', [TareaController::class, 'pdfTareas'])->name('pdf.tareas');
    Route::get('pdf/sanciones/', [RrhhdescuentoController::class, 'pdfDescuentos'])->name('pdf.sanciones');
    Route::get('pdf/asistencias/', [AsistenciaController::class, 'pdfAsistencia'])->name('pdf.asistencias');
    Route::get('pdf/planilla-asistencias/', [AsistenciaController::class, 'pdfPlanillaAsistencia'])->name('pdf.planillaasistencias');
    Route::get('admin/pdf/sueldos/{id}', [SueldoController::class, 'previsualizacion'])->name('pdf.sueldos');
    Route::get('admin/pdf/boletas-sueldo/{id}', [SueldoController::class, 'boletas'])->name('pdf.boletas');


    Route::get('admin/hombre-vivo', ListadoHv::class)->name('admin.hombre_vivo');
    Route::get('pdf/informe/{data}', [CiteinformeController::class, 'previsualizacion'])->name('pdf.informe');
    Route::get('admin/citesinforme', ListadoCiteInforme::class)->middleware('can:admin.generador.informe')->name('admin.citesinformes');

    Route::get('pdf/memorandum/{data}', [CitememorandumController::class, 'previsualizacion'])->name('pdf.memorandum');
    Route::get('admin/citesmemorandum', ListadoCiteMemorandum::class)->middleware('can:admin.generador.memorandum')->name('admin.citesmemorandum');

    Route::get('pdf/cobro/{data}', [CitecobroController::class, 'previsualizacion'])->name('pdf.cobro');
    Route::get('admin/citescobro', ListadoCiteCobro::class)->middleware('can:admin.generador.cobro')->name('admin.citescobro');

    Route::get('pdf/recibo/{data}', [CitereciboController::class, 'previsualizacion'])->name('pdf.recibo');
    Route::get('admin/citesrecibo', ListadoCiteRecibo::class)->middleware('can:admin.generador.recibo')->name('admin.citesrecibo');

    Route::get('pdf/cotizacion/{data}', [CitecotizacionController::class, 'previsualizacion'])->name('pdf.cotizacion');
    Route::get('admin/citescotizacion', ListadoCiteCotizacion::class)->middleware('can:admin.generador.cotizacion')->name('admin.citescotizacion');



    Route::post('/subir-archivo', [UploadsController::class, 'uploadFile'])->name('uploadFile');

    Route::get('pdf/acta-dotacion-cliente/', [CldotacionController::class, 'acta'])->name('pdf.actadotacioncliente');
    Route::get('pdf/acta-dotacion-empleado/{id}/{contrato_id}', [RrhhdotacionController::class, 'acta'])->name('pdf.actadotacionempleado');
    Route::get('pdf/cronograma-mensual', [DesignacioneController::class, 'pdfCronogramaMensual'])->name('pdf.cronogramamensual');
    ////////////////// AREA DE CLIENTES ///////////////////////
    Route::get('admin/usuariocliente/{cliente_id}', Usuariocliente::class)->name('usuariocliente');

    Route::get('customers/personal/{empleado_id}', function ($empleado_id) {
        $empleado = Empleado::find($empleado_id);
        return view('customer.perfilguardia', compact('empleado'));
    })->name('customer.perfilguardia');

    Route::get('customer/visitas', Visitas::class)->name('customer.visitas');
    Route::get('customer/novedades', CustomerNovedades::class)->name('customer.novedades');
    Route::get('customer/rondas', Rondas::class)->name('customer.rondas');
    Route::get('customer/informes', Informes::class)->name('customer.informes');
    Route::get('customer/cobros', Cobros::class)->name('customer.cobros');
    Route::get('customer/recibos', Recibos::class)->name('customer.recibos');
    Route::get('customer/links', Links::class)->name('customer.links');
    Route::get('customer/listado-solicitudes', CustomerListadoSolicitudes::class)->name('customer.listadosolicitudes');
    Route::get('customer/{propietario_id}/{cliente_id}/aprobaciones', CustomerAprobaciones::class)->name('customer.aprobaciones');
    Route::get('customer/listado-residencias', CustomerListadoResidencias::class)->name('customer.listadoresidencias');
    Route::get('customer/listado-propietarios', CustomerListadoPropietarios::class)->name('customer.listadopropietarios');

    Route::get('propietarios/mis-residencias', MisResidencias::class)->name('misresidencias');
    Route::get('propietarios/pases', Pases::class)->name('propietarios.pases');
    Route::get('propietarios/resumen/{id}', [PaseingresoController::class, 'resumen'])->name('resumenpase');

    Route::get('tools/passwords-propietarios', [App\Http\Controllers\ToolsController::class, 'passwordsPropietarios'])->name('tools.passwords-propietarios');

    // RUTAS DEL NAVBAR


    Route::get('notifications/get', [NotificationsController::class, 'getNotificationsData'])
        ->name('notifications.get');

    // Route::get('notifications/show', [NotificationsController::class, 'showAll'])
    //     ->name('notifications.show');
});

Route::middleware('throttle:10,1')->get('formulario-cobro/{link_id}', [FormularioAirbnbController::class, 'cobro'])->name('formcobro');
Route::middleware('throttle:10,1')->get('formulario-recibo/{link_id}', [FormularioAirbnbController::class, 'recibo'])->name('formrecibo');
Route::middleware('throttle:10,1')->get('formulario-informe/{link_id}', [FormularioAirbnbController::class, 'informe'])->name('forminforme');
Route::middleware('throttle:10,1')->get('formulario-cotizacion/{link_id}', [FormularioAirbnbController::class, 'cotizacion'])->name('formcotizacion');

Route::get('formulario-propietarios/{clienteId}', RegistroPropietario::class)->name('regpropietario');
Route::middleware('throttle:500,1')->get('propietario/resumen/{id}', [PropietarioController::class, 'resumen'])
    ->name('propietario.resumen');

Route::middleware('throttle:10,1')->get('formulario-airbnb/{link_id}', [FormularioAirbnbController::class, 'index'])->name('formairbnb');
Route::middleware('throttle:10,1')->get('register-success/{registro_id}', [FormularioAirbnbController::class, 'regsuccess'])->name('regsuccess');
Route::middleware('throttle:10,1')->get('downloadqr/{contenido}', [FormularioAirbnbController::class, 'descargarQr'])->name('downloadqr');
Route::middleware('throttle:10,1')->get('downloadpdf/{id}', [FormularioAirbnbController::class, 'descargarPdf'])->name('downloadpdf');
