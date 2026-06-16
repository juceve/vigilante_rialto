<?php

namespace App\Http\Controllers;

use App\Models\Dialibre;
use Illuminate\Http\Request;

/**
 * Class DialibreController
 * @package App\Http\Controllers
 */
class DialibreController extends Controller
{
    public function data($designacione_id = NULL)
    {
        $diaslibres = Dialibre::query()
            ->when($designacione_id, function ($query) use ($designacione_id) {
                $query->where("designacione_id", $designacione_id);
            })
            ->get();

        return json_encode($diaslibres);
    }
}
