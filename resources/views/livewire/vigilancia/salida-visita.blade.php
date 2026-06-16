<div>
    @section('title')
    Registro Salida de Visita
    @endsection
    <div class="row mb-3">
        <div class="col-1">
            <a href="javascript:history.back();" class="text-silver"><i class="fas fa-arrow-circle-left fa-2x"></i></a>
        </div>
        <div class="col-10">
            <h4 class="text-secondary text-center">SALIDA DE VISITA</h4>
        </div>
        <div class="col-1"></div>

    </div>
    <div class="table-responsive">
        <table class="table table-bordered">
            <tr class="table-success">
                <td><strong>Ingreso:</strong></td>
                <td>{{$visita->created_at}}</td>
            </tr>
            <tr>
                <td><strong>Visitante:</strong></td>
                <td>{{$visita->nombre}}</td>
            </tr>
            <tr>
                <td><strong>Doc. Identidad:</strong></td>
                <td>{{$visita->docidentidad}}</td>
            </tr>
            <tr>
                <td><strong>Residente:</strong></td>
                <td>{{$visita->residente}}</td>
            </tr>
            <tr>
                <td><strong>Nro. Vivienda:</strong></td>
                <td>{{$visita->nrovivienda}}</td>
            </tr>
            <tr>
                <td><strong>Motivo visita:</strong></td>
                <td>{{$visita->motivo->nombre}}</td>
            </tr>
            @if ($visita->otros!="")
            <tr>
                <td><strong>Otros:</strong></td>
                <td>{{$visita->otros}}</td>
            </tr>
            @endif
            <tr>
                <td><strong>Observaciones:</strong></td>
                <td>
                    <input type="search" class="form-control" wire:model='observaciones' />
                </td>
            </tr>
        </table>
        @if (count($imgs))
        <label><strong>Capturas:</strong></label>
        <hr>
        <div class="row">
            @foreach ($imgs as $item)
            <div class="col-6 col-md-4">
                <img src="{{asset('storage/'.$item)}}" class="img-fluid" style="max-height: 150px;">
            </div>
            @endforeach
        </div>
        @endif
        <hr>
        <label><strong>Nuevas capturas:</strong></label>
        <div class="container-fluid mt-3" wire:ignore>
            <form id="uploadForm">
                <div class="row mb-3 input-row" id="inputRow0">
                    <div class="col">
                        <input type="file" class="form-control" id="fileInput0" name="fileInput0"
                            onchange="CARGAFOTO('fileInput0')" accept="image/*,audio/*" capture="camera">
                    </div>
                    <div class="col-auto" id="thumbnailContainer0"></div>
                    <div class="col-auto d-none" id="deleteButtonContainer0">
                        <button type="button" class="btn btn-danger"
                            onclick="deleteInput('inputRow0');remArray(0)">x</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="d-grid mt-3 mb-3">
        <button type="button" class="btn btn-primary py-3" wire:click='marcarSalida' wire:loading.attr="disabled">
            Marcar Salida
            <div wire:loading wire:target="marcarSalida">
                <div class="spinner-border spinner-border-sm" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </button>
    </div><br><br>
</div>

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/styleVigilancia.css') }}">
@endpush

@section('js')

<script>
    let inputCount = 1;
function remArray(id){
    Livewire.emit('deleteInput',id);
}

function CARGAFOTO(inputId) {
    var srcEncoded;
    @this.set('filename',"");
    const inputElement = document.getElementById(inputId);
    const file = inputElement.files[0];
    if (file) {
        const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!validTypes.includes(file.type)) {
                document.getElementById(inputId).value="";
                Swal.fire({
                    title: "Error",
                    text: "Solo se permiten imagenes.",
                    icon: "error"
                });
                event.preventDefault();
                return;
            }

        const reader = new FileReader();
        reader.onload = function (e) {
            const thumbnailContainerId = `thumbnailContainer${inputId.replace('fileInput', '')}`;
            createThumbnail(e.target.result, thumbnailContainerId);
            showDeleteButton(inputId);
            createNewFileInput();

            const imgElement = document.createElement("img");
            imgElement.src = event.target.result;

            imgElement.onload = function (e) {
                const canvas = document.createElement("canvas");
                const MAX_WIDTH = 400;

                const scaleSize = MAX_WIDTH / e.target.width;
                canvas.width = MAX_WIDTH;
                canvas.height = e.target.height * scaleSize;

                const ctx = canvas.getContext("2d");

                ctx.drawImage(e.target, 0, 0, canvas.width, canvas.height);

                srcEncoded = ctx.canvas.toDataURL(e.target, "image/jpeg");

                @this.cargaImagenBase64(srcEncoded);
            };
        }
        reader.readAsDataURL(file);
    }
    inputElement.disabled=true;
}

function createThumbnail(src, containerId) {
    const thumbnailContainer = document.getElementById(containerId);
    thumbnailContainer.innerHTML = '';
    const img = document.createElement('img');
    img.src = src;
    img.className = 'img-thumbnail';
    img.style.maxWidth = '100px';
    thumbnailContainer.appendChild(img);
}

function showDeleteButton(inputId) {
    const deleteButtonContainerId = `deleteButtonContainer${inputId.replace('fileInput', '')}`;
    const deleteButtonContainer = document.getElementById(deleteButtonContainerId);
    deleteButtonContainer.classList.remove('d-none');
}

function createNewFileInput() {
    const form = document.getElementById('uploadForm');
    const divRow = document.createElement('div');
    divRow.className = 'row mb-3 input-row';
    divRow.id = `inputRow${inputCount}`;

    const divInput = document.createElement('div');
    divInput.className = 'col';

    const label = document.createElement('label');
    label.className = 'form-label';
    label.textContent = 'Upload Image';

    const input = document.createElement('input');
    input.type = 'file';
    input.className = 'form-control';
    input.id = `fileInput${inputCount}`;
    input.name = `fileInput${inputCount}`;
    input.accept = 'image/*,audio/*';
    input.setAttribute('capture', `camera`);
    input.setAttribute('onchange', `CARGAFOTO('${input.id}')`);

    const divThumbnail = document.createElement('div');
    divThumbnail.className = 'col-auto';
    divThumbnail.id = `thumbnailContainer${inputCount}`;

    const divButton = document.createElement('div');
    divButton.className = 'col-auto d-none';
    divButton.id = `deleteButtonContainer${inputCount}`;
    const button = document.createElement('button');
    button.type = 'button';
    button.className = 'btn btn-danger';
    button.textContent = 'x';
    button.setAttribute('onclick', `deleteInput('${divRow.id}');remArray('${inputCount}')`);
    // button.setAttribute('wire:click', `deleteInput('${inputCount}')`);

    // divInput.appendChild(label);
    divInput.appendChild(input);
    divButton.appendChild(button);
    divRow.appendChild(divInput);
    divRow.appendChild(divThumbnail);
    divRow.appendChild(divButton);

    form.appendChild(divRow);
    inputCount++;
}

function deleteInput(rowId) {
    const row = document.getElementById(rowId);
    row.remove();
    // Ensure at least one empty input file remains
    const inputs = document.querySelectorAll('input[type="file"]');
    if (inputs.length === 0) {
        createNewFileInput();
    }
}
</script>
@endsection
