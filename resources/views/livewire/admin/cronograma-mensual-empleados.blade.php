<div>
    @section('title')
    Cronograma Mensual de Días Libres
    @endsection

    @section('content_header')
    <div class="container-fluid">
        <h4>Cronograma Mensual de Días Libres</h4>
    </div>
    @endsection
    <div class="container-fluid">
        <div class="card">

            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-3 mb-3">
                        <label class="form-label">Mes</label>
                        <select class="form-control form-control-sm" wire:model="month">
                            @php
                            $meses =
                            ['1'=>'Enero','2'=>'Febrero','3'=>'Marzo','4'=>'Abril','5'=>'Mayo','6'=>'Junio','7'=>'Julio','8'=>'Agosto','9'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre'];
                            @endphp
                            @foreach($meses as $num => $name)
                            <option value="{{ $num }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-3 mb-3">
                        <label class="form-label">Año</label>
                        <select class="form-control form-control-sm" wire:model="year">
                            @foreach($years as $y)
                            <option value="{{ $y }}">{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-3 mb-3 d-flex align-items-end">
                        <button class="btn btn-primary btn-sm" wire:click="loadData" wire:loading.attr="disabled">
                            <i class="fas fa-search"></i> Aplicar
                            <span wire:loading wire:target="loadData">
                                <i class="fas fa-spinner fa-spin"></i>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div wire:loading wire:target="loadData" class="alert alert-info">
            <i class="fas fa-spinner fa-spin"></i> Cargando datos...
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-table"></i>
                    @php
                    $mesGestion =\Carbon\Carbon::create($year, $month, 1)->locale('es')->isoFormat('MMMM YYYY');
                    @endphp
                    Cronograma - {{ strtoupper($mesGestion) }}
                </h3>
                @if (count($employees))
                <br>
                <small><strong>Empleados con Designaciones vigentes</strong></small>
                @endif

            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                    <table class="table table-bordered table-sm table-striped table-hover" style="font-size: 12px;">
                        <thead class="thead-light" style="position: sticky; top: 0; z-index: 1020;">
                            <tr>
                                <th class="bg-light">Empleado</th>
                                @foreach($daysInMonth as $d)
                                <th class="text-center" style="width:40px;">{{ $d }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employees as $emp)
                            <tr>
                                <td class="bg-light align-middle">{{ $emp['name'] }}</td>
                                @foreach($daysInMonth as $d)
                                <td class="text-center align-middle">
                                    @if(in_array($d, $emp['days']))
                                    <span class="badge badge-success" style="font-size: 12px;"
                                        title="Día Libre: {{ $d.'/'.str_pad($month, 2, " 0", STR_PAD_LEFT).'/'.$year
                                        }}">L</span>
                                    @else
                                    <span class="text-muted">-</span>
                                    @endif
                                </td>
                                @endforeach
                            </tr>
                            @empty
                            <tr>
                                <td class="text-center text-muted" colspan="{{ count($daysInMonth) + 1 }}">
                                    <i class="fas fa-info-circle"></i> No se encontraron empleados con designaciones
                                    vigentes para el mes/año seleccionados.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if(!empty($employees))
            <div class="card-footer">
                <small class="text-muted">
                    <i class="fas fa-info-circle"></i>
                    Total de empleados: <strong>{{ count($employees) }}</strong> |
                    <span class="badge badge-success">L</span> = Día libre
                </small>
                <button class="btn btn-sm btn-danger float-right col-md-3" wire:click="exportarPDF"><i class="fas fa-file-pdf"></i> Exportar</button>
            </div>
            @endif
        </div>
    </div>
</div>
@section('js')
<script>
    Livewire.on('renderizarpdf', data => {
            var win = window.open("../pdf/cronograma-mensual/", '_blank');
            win.focus();
        });
</script>
@endsection