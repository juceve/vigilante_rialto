<div>

    <div class="container-fluid">

        <div class="card">
            <div class="card-header">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <label>
                        SOLICITUD DE REGISTROS AIRBNB
                    </label>
                    <a href="javascript:history.back();" class="btn btn-sm btn-light shadow-sm"><i
                            class="fa fa-long-arrow-left fa-sm"></i>
                        Volver</a>
                </div>
            </div>
            <div class="card-body">
                <label for="">Filtrar:</label>
                <div class="row">
                    <div class="col-12 col-md-4 col-xl-3">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Desde</span>
                            </div>
                            <input type="date" class="form-control" wire:model='inicio' aria-label="inicio"
                                aria-describedby="basic-addon1">
                        </div>

                    </div>
                    <div class="col-12 col-md-4 col-xl-3">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Hasta</span>
                            </div>
                            <input type="date" class="form-control" wire:model='final' aria-label="final"
                                aria-describedby="basic-addon1">
                        </div>
                    </div>
                    <div class="col-12 col-md-4 col-xl-3">

                    </div>
                    <div class="col-12 col-md-4 col-xl-3">
                        <button class="btn btn-info btn-block" data-toggle='modal' data-target='#modalRegistro'
                            wire:click='nuevoReg'>
                            NUEVA SOLICITUD <i class="fa fa-plus"></i>
                        </button>
                    </div>
                </div>
                <hr>




                <div class="table-responsive">
                    @if (!is_null($resultados))
                        <div class="row w-100">
                            <div class="col-12 mb-3">
                                <div class="input-group ">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i
                                                class="fa fa-search"></i></span>
                                    </div>
                                    <input type="search" class="form-control" placeholder="Busqueda..."
                                        aria-label="Busqueda..." aria-describedby="basic-addon1"
                                        wire:model.debounce.500ms='search'>
                                </div>
                            </div>

                        </div>
                    @endif
                    <table class="table table-bordered table-striped table-sm"
                        style="vertical-align: middle; font-size: 13px;">

                        <thead>
                            <tr class="table-info">
                                <th>ID</th>
                                <th>SOLICITANTE</th>
                                <th>DOC. IDENTIDAD</th>
                                <th>CELULAR</th>
                                <th class="text-center">VIGENCIA</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!is_null($resultados))
                                @forelse ($resultados as $item)
                                    <tr>
                                        <td class="align-middle">{{ $item->id }}</td>
                                        <td class="align-middle">{{ $item->solicitante }}</td>
                                        <td class="align-middle">{{ $item->cedula }}</td>
                                        <td class="align-middle">{{ $item->celular }}</td>

                                        <td class="text-center align-middle">
                                            @if ($item->vigencia >= date('Y-m-d H:i:s'))
                                                <span class="badge badge-pill badge-success "
                                                    style="font-size: 12px">{{ $item->vigencia }}</span>
                                            @else
                                                <span class="badge badge-pill badge-secondary "
                                                    style="font-size: 12px">{{ $item->vigencia }}</span>
                                            @endif
                                        </td>
                                        <td class="align-middle" align="right">
                                            <button class="btn btn-primary btn-sm" title="Ver info"
                                                wire:click='verInfo({{ $item->id }})' data-toggle='modal'
                                                data-target='#modalInfo'>
                                                <i class="fa fa-eye"></i>
                                            </button>
                                            <button class="btn btn-success btn-sm" title="Enviar Link"
                                                wire:click='sendWhatsAppLink({{ $item->id }})'>
                                                <i class="fa fa-whatsapp"></i>
                                            </button>

                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center" colspan="6">No se econtraron resultados.</td>
                                    </tr>
                                @endforelse
                            @else
                                <tr>
                                    <td class="text-center" colspan="6">No se econtraron resultados.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-end">
                    @if (!is_null($resultados))
                        {{ $resultados->links() }}
                    @endif

                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalInfo" tabindex="-1" aria-labelledby="modalInfoLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalInfoLabel"><strong>INFO REGISTRO - ID:
                            {{ $airbnblink->id }}</strong></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div wire:loading>

                        <div class="spinner-border text-success" role="status">
                            <span class="sr-only">Cargando...</span>
                        </div>

                    </div>
                    <div wire:loading.remove>
                        <div class="row">
                            <div class="col-12">

                                <table class="table table-bordered table-striped">
                                    <tr>
                                        <td><strong>SOLICITANTE:</strong></td>
                                        <td>{{ $airbnblink->solicitante }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>DOC. IDENTIDAD:</strong></td>
                                        <td>{{ $airbnblink->cedula }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>CELULAR:</strong></td>
                                        <td>{{ $airbnblink->celular }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>VÁLIDO HASTA:</strong></td>
                                        <td>{{ $airbnblink->vigencia }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>OBSERVACIONES:</strong></td>
                                        <td>{{ $airbnblink->observaciones }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>LINK GENERADO:</strong></td>
                                        <td style=" max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                            {{ $airbnblink->link }} <br>
                                            <button class="btn btn-warning btn-sm" style="height: 25px;font-size: 11px;vertical-align: middle" onclick="copyToClipboard()">
                                                Copian Link <i class="fa fa-link"></i>
                                            </button><input type="hidden" id="hiddenInput" value="{{ $airbnblink->link }}">   
                                        </td>
                                    </tr>
                                    {{-- <tr>
                                        <td>
                                            
                                        </td>
                                        <td>
                                            
                                            
                                        </td>
                                    </tr> --}}
                                    <tr>
                                        <td>
                                            <strong>REGISTROS REALIZADOS:</strong>
                                        </td>
                                        <td>
                                            @if ($airbnblink->airbnbtravelers->count() > 0)
                                                @foreach ($airbnblink->airbnbtravelers as $item)
                                                    <button class="btn btn-info btn-sm"
                                                        wire:click='exportarPDF({{ $item->id }})'>
                                                        Descargar Reg.: {{ str_pad($item->id, 6, '0', STR_PAD_LEFT) }}
                                                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                    </button><br>
                                                @endforeach
                                            @else
                                                <i>No existen registros</i>
                                            @endif

                                        </td>
                                    </tr>

                                </table>
                            </div>

                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">CERRAR</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="modalRegistro" tabindex="-1" aria-labelledby="modalRegLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title" id="modalRegLabel"><strong>SOLICITUD DE REGISTRO AIRBNB</strong></span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">


                    <div class="row">

                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                {{ Form::label('solicitante') }}
                                {{ Form::text('solicitante', null, ['class' => 'form-control text-uppercase' . ($errors->has('solicitante') ? ' is-invalid' : ''), 'placeholder' => 'Nombre Solicitante', 'wire:model.lazy' => 'solicitante']) }}
                                {!! $errors->first('solicitante', '<div class="invalid-feedback">:message</div>') !!}
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                {{ Form::label('cedula') }}
                                {{ Form::text('cedula', null, ['class' => 'form-control' . ($errors->has('cedula') ? ' is-invalid' : ''), 'placeholder' => 'Cedula', 'wire:model.lazy' => 'cedula']) }}
                                {!! $errors->first('cedula', '<div class="invalid-feedback">:message</div>') !!}
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                {{ Form::label('celular') }}
                                {{ Form::text('celular', null, ['class' => 'form-control' . ($errors->has('celular') ? ' is-invalid' : ''), 'placeholder' => 'Celular', 'wire:model.lazy' => 'celular']) }}
                                {!! $errors->first('celular', '<div class="invalid-feedback">:message</div>') !!}
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                {{ Form::label('observaciones') }}
                                {{ Form::text('observaciones', null, ['class' => 'form-control text-uppercase' . ($errors->has('observaciones') ? ' is-invalid' : ''), 'placeholder' => 'Observaciones', 'wire:model.lazy' => 'observaciones']) }}
                                {!! $errors->first('observaciones', '<div class="invalid-feedback">:message</div>') !!}
                            </div>
                        </div>

                    </div>

                    <hr>
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <button type="button" class="btn btn-secondary btn-block"
                                data-dismiss="modal">CERRAR</button>
                        </div>
                        <div class="col-12 col-md-4">
                            <button type="button" class="btn btn-primary btn-block" data-dismiss="modal"
                                wire:click='registrar'>REGISTRAR</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        Livewire.on("success", msg => {
            Swal.fire({
                title: "Excelente!",
                text: "Registro exitoso. Nro:" + msg,
                icon: "success"
            });
        });
        Livewire.on("error", msg => {
            Swal.fire({
                title: "Error!",
                text: msg,
                icon: "error"
            });
        });

        Livewire.on("open-whatsapp", event => {
            // console.log(event.url);

            window.open(event.url, '_blank');
        });
    </script>
    <script>
        function copyToClipboard() {


            // Obtener el valor del input hidden
            const hiddenInput = document.getElementById('hiddenInput');
            if (!hiddenInput || !hiddenInput.value) {
                alert('No se encontró el elemento o está vacío');
                return;
            }

            // Verificar si el navegador soporta navigator.clipboard
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(hiddenInput.value)
                    .then(() => {
                        Swal.fire({
                            toast: true,
                            position: 'top-end', // Puedes usar 'top-end', 'top', 'bottom-end', etc.
                            icon: 'success',
                            title: 'Texto copiado al portapapeles',
                            showConfirmButton: false,
                            timer: 3000, // Tiempo en milisegundos (3 segundos)
                            timerProgressBar: true
                        });
                    })
                    .catch(err => {
                        console.error('Error al copiar:', err);
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: 'Error al copiar el texto',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });
                    });
            } else {
                // Fallback para navegadores más antiguos
                const tempTextarea = document.createElement('textarea');
                tempTextarea.value = hiddenInput.value;
                document.body.appendChild(tempTextarea);
                tempTextarea.select();
                try {
                    document.execCommand('copy');
                    alert('Texto copiado al portapapeles (método alternativo)');
                } catch (err) {
                    console.error('Error al copiar (método alternativo):', err);
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: 'Error al copiar el texto',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                }
                document.body.removeChild(tempTextarea);
            }


        }
    </script>
@endsection
