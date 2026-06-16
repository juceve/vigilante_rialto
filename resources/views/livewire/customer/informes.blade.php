<div>
    @section('page_header')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Registro de Informes</h1>

            <a href="/home" class="btn btn-sm btn-primary shadow-sm"><i class="fas fa-arrow-left fa-sm"></i> Volver</a>
        </div>
    </div>
    @endsection

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-sm-flex align-items-center justify-content-between">
                            <label>
                                REGISTRO DE INFORMES
                            </label>
                            <a href="javascript:history.back();" class="btn btn-sm btn-light shadow-sm"><i
                                    class="fa fa-long-arrow-left fa-sm"></i>
                                Volver</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-9">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-search"></i></span>
                                    </div>
                                    <input type="search" class="form-control" placeholder="Ingrese su busqueda..."
                                        wire:model.debounce.500ms='busqueda'>
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><small>Gestión: </small></span>
                                    </div>
                                    <select class="form-control" wire:model='gestion'>
                                        <option value="{{ date('Y') }}">{{ date('Y') }}</option>
                                        <option value="{{ date('Y') - 1 }}">{{ date('Y') - 1 }}</option>
                                        <option value="{{ date('Y') - 2 }}">{{ date('Y') - 2 }}</option>
                                    </select>
                                </div>
                            </div>

                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" style="font-size: 13px;">
                                <thead class="thead table-info">
                                    <tr class="text-uppercase text-center">
                                        <th>Cite</th>
                                        <th>Fecha</th>
                                        <th>Cliente</th>
                                        <th>Referencia</th>
                                        <th>Estado</th>

                                        <th style="width: 10px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($citeinformes as $citeinforme)
                                    <tr class="text-center">
                                        <td>{{ $citeinforme->cite }}</td>
                                        <td>{{ $citeinforme->fecha }}</td>
                                        <td class="text-left">{{ $citeinforme->cliente }}</td>
                                        <td class="text-left">{{ $citeinforme->referencia }}</td>
                                        <td>
                                            @if ($citeinforme->estado)
                                            <span class="badge badge-pill badge-success">Activo</span>
                                            @else
                                            <span class="badge badge-pill badge-secondary">Anulado</span>
                                            @endif
                                        </td>

                                        <td align="right">
                                            <a class="btn btn-sm btn-info "
                                                href="{{ route('pdf.informe', $citeinforme->id.'|1') }}" title="Reimprimir"
                                                target="_blank"><i class="fa fa-fw fa-print"></i></a>

                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if ($citeinformes)
                        {{ $citeinformes->links() }}
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>
@section('js')


<script>
    Livewire.on('renderizarpdf', data => {
            var win = window.open("../pdf/informe/" + data, '_blank');
            win.focus();
        });
</script>
@endsection
