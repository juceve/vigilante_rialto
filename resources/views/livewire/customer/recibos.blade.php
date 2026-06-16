<div>
    @section('page_header')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Registro de Recibos</h1>

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
                                REGISTRO DE RECIBOS
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
                            <table class="table table-striped table-bordered ">
                                <thead class="table-info">
                                    <tr class="text-uppercase text-center">
                                        <th>Cite</th>

                                        <th>Cliente</th>
                                        <th>Mescobro</th>


                                        <th style="width: 10px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($citerecibos as $citerecibo)
                                    <tr class="text-center">
                                        <td>{{ $citerecibo->cite }}</td>
                                        <td class="text-left">{{ $citerecibo->cliente }}</td>
                                        <td>{{ $citerecibo->mescobro }}</td>

                                        <td class="text-left">
                                            <a class="btn btn-sm btn-info "
                                                href="{{ route('pdf.recibo', $citerecibo->id.'|1') }}" title="Reimprimir"
                                                target="_blank"><i class="fa fa-fw fa-print"></i></a>

                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $citerecibos->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@section('js')
<script>
    Livewire.on('renderizarpdf', data => {
            var win = window.open("../pdf/recibo/" + data, '_blank');
            win.focus();
        });
</script>
@endsection
