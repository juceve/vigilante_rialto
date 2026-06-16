<div>
    @section('title')
        Generador de Documentos
    @endsection
    @section('content_header')
        <h4>Generador de Documentos</h4>
    @endsection

    <div class="container-fluid">
        <div class="card">
            <div class="card-header bg-info">
                <div style="display: flex; justify-content: space-between; align-items: center;">

                    <span id="card_title">
                        <strong>Seleccione un Modelo</strong>
                    </span>

                    <div class="float-right">
                        <a href="javascript:history.back()" class="btn btn-info btn-sm float-right" data-placement="left">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="card card-primary card-outline card-outline-tabs">
                    <div class="card-header p-0 border-bottom-0">
                        <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="informe-tab" data-toggle="pill" href="#informe"
                                    role="tab" aria-controls="informe" aria-selected="true">INFORME</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="memorandum-tab" data-toggle="pill" href="#memorandum"
                                    role="tab" aria-controls="memorandum" aria-selected="false">MEMORANDUM</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="cobro-tab" data-toggle="pill" href="#cobro"
                                    role="tab" aria-controls="cobro" aria-selected="false">COBRO</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="cotizacion-tab" data-toggle="pill" href="#cotizacion"
                                    role="tab" aria-controls="cotizacion" aria-selected="false">COTIZACIÓN</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="recibo-tab" data-toggle="pill" href="#recibo" role="tab"
                                    aria-controls="recibo" aria-selected="false">RECIBO</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-four-tabContent">
                            <div class="tab-pane fade show active" id="informe" role="tabpanel"
                                aria-labelledby="informe-tab">
                                @livewire('admin.partials.pt-informe')
                            </div>
                            <div class="tab-pane fade" id="memorandum" role="tabpanel" aria-labelledby="memorandum-tab">
                                @livewire('admin.partials.pt-memorandum')
                            </div>
                            <div class="tab-pane fade" id="cobro" role="tabpanel" aria-labelledby="cobro-tab">
                                @livewire('admin.partials.pt-cobro')
                            </div>
                            <div class="tab-pane fade" id="cotizacion" role="tabpanel" aria-labelledby="cotizacion-tab">
                                @livewire('admin.partials.pt-cotizacion')
                            </div>
                            <div class="tab-pane fade" id="recibo" role="tabpanel" aria-labelledby="recibo-tab">
                                @livewire('admin.partials.pt-recibo')
                            </div>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
                {{-- <div class="row">
                    
                    <div class="col-12 col-md-3">
                        <button class="btn btn-outline-success py-3 btn-block">Informe</button>
                    </div>
                    <div class="col-12 col-md-3">
                        <button class="btn btn-outline-success py-3 btn-block">Memorandum</button>
                    </div>
                    <div class="col-12 col-md-3">
                        <button class="btn btn-outline-success py-3 btn-block">Cotización</button>
                    </div>
                    <div class="col-12 col-md-3">
                        <button class="btn btn-outline-success py-3 btn-block">Recibo</button>
                    </div>
                </div> --}}
            </div>

        </div>
    </div>
</div>
