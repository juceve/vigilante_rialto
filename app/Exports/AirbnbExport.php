<?php

namespace App\Exports;

use App\Models\Airbnbtraveler;
use App\Models\Cliente;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AirbnbExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        $cliente_id = Session::get('cliente_id-oper');
        $parametros = Session::get('airbnb-param');
        $inicio = $parametros[0];
        $final = $parametros[1];
        $search = $parametros[2];
        $cliente=Cliente::find($cliente_id);
        $resultados = $travelers = Airbnbtraveler::join('airbnblinks', 'airbnbtravelers.airbnblink_id', '=', 'airbnblinks.id')
        ->where('airbnblinks.cliente_id', $cliente_id)
        ->where('airbnbtravelers.status', '!=', 'FINALIZADO')
        ->when(!empty($inicio) && !empty($final), function ($query) use ($inicio, $final) {
            return $query->whereBetween('airbnbtravelers.created_at', [$inicio . ' 00:00:00', $final . ' 23:59:59']);
        })
        ->when(!empty($search), function ($query) use ($search) {
            return $query->where('airbnbtravelers.name', 'LIKE', "%$search%");
        })
        ->select('airbnbtravelers.*')
        ->orderBy('airbnbtravelers.id', 'asc')->get();

        return view('excels.airbnb', compact('resultados', 'parametros','cliente'));
    }
}
