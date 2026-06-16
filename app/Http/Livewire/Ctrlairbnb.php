<?php

namespace App\Http\Livewire;

use App\Exports\AirbnbExport;
use App\Models\Airbnbtraveler;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Ctrlairbnb extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $inicio, $final, $search = "";

    public function mount()
    {
        $this->inicio = date('Y-m-') . '01';
        $this->final = date('Y-m-d');
    }
    public function render()
    {
        $cliente_id = Session::get('cliente_id-oper');

        $inicio = $this->inicio;
        $final = $this->final;
        $search = $this->search;
        $travelers = Airbnbtraveler::join('airbnblinks', 'airbnbtravelers.airbnblink_id', '=', 'airbnblinks.id')
            ->where('airbnblinks.cliente_id', $cliente_id)
            ->where('airbnbtravelers.status', '!=', 'FINALIZADO')
            ->when(!empty($inicio) && !empty($final), function ($query) use ($inicio, $final) {
                return $query->whereBetween('airbnbtravelers.created_at', [$inicio . ' 00:00:00', $final . ' 23:59:59']);
            })
            ->when(!empty($search), function ($query) use ($search) {
                return $query->where('airbnbtravelers.name', 'LIKE', "%$search%");
            })
            ->select('airbnbtravelers.*')
            ->orderBy('airbnbtravelers.departure_date', 'asc')
            ->paginate(10);

           
            $parametros = array($this->inicio, $this->final, $this->search);
            Session::put('airbnb-param', $parametros);

        return view('livewire.ctrlairbnb', compact('travelers', 'cliente_id'));
    }

    public function exporExcel()
    {
        
        return Excel::download(new AirbnbExport(), 'Registros_Airbnb_' .  date('YmdHis') . '.xlsx');
    }

    public function updatedInicio()
    {
        $this->resetPage();
    }
    public function updatedFinal()
    {
        $this->resetPage();
    }
    public function updatedSearch()
    {
        $this->resetPage();
    }
}
