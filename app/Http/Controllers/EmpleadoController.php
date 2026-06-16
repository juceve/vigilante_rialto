<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Empleado;
use App\Models\Oficina;
use App\Models\Rrhhcontrato;
use App\Models\Tipodocumento;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Intervention\Image\ImageManagerStatic as Image;

/**
 * Class EmpleadoController
 * @package App\Http\Controllers
 */
class EmpleadoController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:empleados.index')->only('index');
        $this->middleware('can:empleados.create')->only('create', 'store');
        $this->middleware('can:empleados.edit')->only('edit', 'update');
        $this->middleware('can:empleados.destroy')->only('destroy');
    }

    /**
     * Guarda una imagen en base64 en el storage
     */
    private function saveBase64Image($base64Data, $empleadoId, $prefix)
    {
        $imageData = explode(';base64,', $base64Data);

        if (count($imageData) == 2) {
            $image = base64_decode($imageData[1]);
            $filename = $prefix . $empleadoId . '.png';
            $directory = storage_path('app/public/images/empleados');

            // Crear directorio si no existe
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            $path = $directory . DIRECTORY_SEPARATOR . $filename;
            Image::make($image)->save($path);

            return 'images/empleados/' . $filename;
        }

        return null;
    }

    public function index()
    {

        return view('admin.empleado.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $empleado = new Empleado();
        $areas = Area::all()->pluck('nombre', 'id');
        $tipodocs = Tipodocumento::all()->pluck('name', 'id');
        $oficinas = Oficina::all()->pluck('nombre', 'id');
        return view('admin.empleado.create', compact('empleado', 'areas', 'tipodocs', 'oficinas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Empleado::$rules);

        $empleado = Empleado::create($request->all());

        if ($request->generarusuario == 'on') {
            $area = Area::find($request->area_id);
            $usuario = User::create([
                "name" => $empleado->nombres . " " . $empleado->apellidos,
                "email" => $empleado->email,
                "password" => bcrypt($empleado->cedula),
                "template" => $area->template,
                "status" => true
            ]);
            $empleado->user_id = $usuario->id;
            $empleado->save();
        }


        //CONVERSION DE IMG64
        if ($request->perfil64) {
            $imgPath = $this->saveBase64Image($request->perfil64, $empleado->id, 'perfil');
            if ($imgPath) {
                $empleado->imgperfil = $imgPath;
            }
        }

        if ($request->anverso64) {
            $imgPath = $this->saveBase64Image($request->anverso64, $empleado->id, 'anverso');
            if ($imgPath) {
                $empleado->cedulaanverso = $imgPath;
            }
        }

        if ($request->reverso64) {
            $imgPath = $this->saveBase64Image($request->reverso64, $empleado->id, 'reverso');
            if ($imgPath) {
                $empleado->cedulareverso = $imgPath;
            }
        }

        $empleado->save();

        return redirect()->route('empleados.index')
            ->with('success', 'Empleado creado correctamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $empleado = Empleado::find($id);

        return view('admin.empleado.show', compact('empleado'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $empleado = Empleado::find($id);
        $areas = Area::all()->pluck('nombre', 'id');
        $tipodocs = Tipodocumento::all()->pluck('name', 'id');
        $oficinas = Oficina::all()->pluck('nombre', 'id');
        return view('admin.empleado.edit', compact('empleado', 'areas', 'tipodocs', 'oficinas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Empleado $empleado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Empleado $empleado)
    {
        request()->validate([
            'nombres' => 'required',
            'apellidos' => 'required',
            'cedula' => 'required|min:3',
            'direccion' => 'required',
            'telefono' => 'required',
            'email' => ['required', Rule::unique('empleados')->ignore($empleado)],
        ]);
        $area = Area::find($request->area_id);
        $empleado->update($request->all());
        if ($request->generarusuario == 'on') {

            $usuario = User::create([
                "name" => $empleado->nombres . " " . $empleado->apellidos,
                "email" => $empleado->email,
                "password" => bcrypt($empleado->cedula),
                "template" => $area->template,
                "status" => true
            ]);
            $empleado->user_id = $usuario->id;
            $empleado->save();
        } else {
            if ($empleado->user_id) {
                $user = User::find($empleado->user_id);
                $user->template = $area->template;
                $user->save();
            }
        }


        //CONVERSION DE IMG64
        if ($request->perfil64) {
            $imgPath = $this->saveBase64Image($request->perfil64, $empleado->id, 'perfil');
            if ($imgPath) {
                $empleado->imgperfil = $imgPath;
            }
        }

        if ($request->anverso64) {
            $imgPath = $this->saveBase64Image($request->anverso64, $empleado->id, 'anverso');
            if ($imgPath) {
                $empleado->cedulaanverso = $imgPath;
            }
        }

        if ($request->reverso64) {
            $imgPath = $this->saveBase64Image($request->reverso64, $empleado->id, 'reverso');
            if ($imgPath) {
                $empleado->cedulareverso = $imgPath;
            }
        }

        $empleado->save();

        return redirect()->route('empleados.edit', $empleado->id)
            ->with('success', 'Empleado editado correctamente');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $empleado = Empleado::find($id);

            if ($empleado->user_id) {
                $usuario = User::find($empleado->user_id)->delete();
            }
            $empleado->delete();
            DB::commit();
            return redirect()->route('empleados.index')
                ->with('success', 'Empleado eliminado correctamente');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('empleados.index')
                ->with('error', 'Ha ocurrido un error');
        }
    }

    public function pdfKardex($empleadoId)
    {
        $empleado = Empleado::find($empleadoId);

        $hoy = Carbon::now()->toDateString();
        $contrato = Rrhhcontrato::where('empleado_id', $empleadoId)
            ->whereDate('fecha_inicio', '<=', $hoy)
            ->where(function ($query) use ($hoy) {
                $query->whereNull('fecha_fin')
                    ->orWhereDate('fecha_fin', '>=', $hoy);
            })
            ->where('activo', true)
            ->orderBy('fecha_inicio', 'asc')
            ->first();
        $pdf = Pdf::loadView('pdfs.kardex-empleado', compact('empleado', 'contrato'))
            ->setPaper('letter', 'portrait');

        // Ojo: aquí trabajamos con el objeto Dompdf real
        $dompdf = $pdf->getDomPDF();
        $canvas = $dompdf->getCanvas();
        $fontMetrics = new \Dompdf\FontMetrics($canvas, $dompdf->getOptions());

        $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
            $font = $fontMetrics->getFont("helvetica", "normal");
            $size = 9;
            $text = "Página $pageNumber de $pageCount";

            // Coordenadas X,Y (ajústalas según tu hoja)
            $x = $canvas->get_width() - 100;
            $y = $canvas->get_height() - 30;

            $canvas->text($x, $y, $text, $font, $size);
        });

        return $pdf->stream("kardex-empleado.pdf");

        // return view('pdfs.kardex-empleado', compact('empleado','contrato'));
    }

    
}
