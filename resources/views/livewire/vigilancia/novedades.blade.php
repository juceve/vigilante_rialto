<div>
    @section('title')
        Novedades
    @endsection

    <!-- Header Corporativo -->
    <div style="margin-top: 85px; background: linear-gradient(135deg, #1e3a8a, #1e293b); padding: 1rem 0; margin-bottom: 1.5rem; box-shadow: 0 2px 8px rgba(30, 41, 59, 0.1);">
        <div class="container">
            <div style="display: flex; align-items: center; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border-radius: 12px; padding: 1rem; box-shadow: 0 2px 8px rgba(30, 41, 59, 0.1);">
                <a href="{{ route('home') }}" style="width: 50px; height: 50px; background: linear-gradient(135deg, #1e3a8a, #1e293b); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; text-decoration: none; font-size: 1.2rem; margin-right: 1rem;">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div style="flex: 1; text-align: center;">
                    <h1 style="font-size: 1.3rem; font-weight: 700; color: #1e293b; margin: 0; letter-spacing: 0.5px;">NOVEDADES</h1>
                    <p style="font-size: 0.85rem; color: #64748b; margin: 0.2rem 0 0 0; font-weight: 500;">Reporte de Incidentes</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container px-3">
        <!-- Título de Sección -->
        <div style="text-align: center; margin-bottom: 2rem;">
            <div style="display: inline-flex; align-items: center; gap: 0.5rem; background: linear-gradient(135deg, #334155, #1e293b); color: white; padding: 0.8rem 1.5rem; border-radius: 25px; box-shadow: 0 4px 12px rgba(51, 65, 85, 0.3);">
                <i class="fas fa-plus-circle"></i>
                <span style="font-weight: 600; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">NUEVO REGISTRO</span>
            </div>
        </div>

        <div class="row">
            <!-- Sección de Anotaciones -->
            <div class="col-12 col-md-6 mb-4">
                <div style="background: white; border-radius: 16px; box-shadow: 0 4px 16px rgba(30, 41, 59, 0.15); overflow: hidden; border: 2px solid #0891b2;">
                    <div style="background: linear-gradient(135deg, #0891b2, #0e7490); color: white; padding: 1rem; text-align: center;">
                        <div style="display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                            <i class="fas fa-edit" style="font-size: 1.2rem;"></i>
                            <h6 style="margin: 0; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Anotaciones</h6>
                        </div>
                    </div>
                    <div style="padding: 1.5rem;">
                        <textarea class="form-control" id="txtinforme" wire:model.lazy='informe' rows="6"
                                placeholder="Describa detalladamente la novedad o incidente observado..."
                                style="border: 2px solid #e2e8f0; border-radius: 12px; padding: 0.8rem 1rem; font-size: 0.9rem; transition: all 0.3s ease; background: white; min-height: 150px; resize: vertical; font-family: inherit; line-height: 1.5;"
                                onfocus="this.style.borderColor='#0891b2'; this.style.boxShadow='0 0 0 3px rgba(8, 145, 178, 0.1)'"
                                onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'"></textarea>
                    </div>
                </div>
            </div>

            <!-- Sección de Capturas -->
            <div class="col-12 col-md-6 mb-4">
                <div style="background: white; border-radius: 16px; box-shadow: 0 4px 16px rgba(30, 41, 59, 0.15); overflow: hidden; border: 2px solid #d97706;">
                    <div style="background: linear-gradient(135deg, #d97706, #f59e0b); color: white; padding: 1rem; text-align: center;">
                        <div style="display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                            <i class="fas fa-camera" style="font-size: 1.2rem;"></i>
                            <h6 style="margin: 0; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Capturas</h6>
                        </div>
                    </div>
                    <div style="padding: 1.5rem;">
                        <div class="container-fluid" wire:ignore>
                            <form id="uploadForm">
                                <div class="row mb-3 input-row" id="inputRow0">
                                    <div class="col">
                                        <input type="file" class="form-control" id="fileInput0" name="fileInput0"
                                            onchange="CARGAFOTO('fileInput0')" accept="image/*,audio/*" capture="camera"
                                            style="border: 2px dashed #cbd5e1; border-radius: 12px; padding: 0.8rem; background: #f8fafc; transition: all 0.3s ease;"
                                            onfocus="this.style.borderColor='#d97706'; this.style.background='rgba(217, 119, 6, 0.05)'"
                                            onblur="this.style.borderColor='#cbd5e1'; this.style.background='#f8fafc'">
                                    </div>
                                    <div class="col-auto" id="thumbnailContainer0"></div>
                                    <div class="col-auto d-none" id="deleteButtonContainer0">
                                        <button type="button" class="btn btn-danger btn-sm"
                                            onclick="deleteInput('inputRow0');remArray(0)"
                                            style="border-radius: 8px; font-size: 0.8rem; padding: 0.4rem 0.8rem;">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botón de Envío -->
        <div class="d-grid mt-4">
            <button class="btn" id="enviar" onclick="activarGeolocalizacionYEnviar()"
                    style="background: linear-gradient(135deg, #059669, #047857); color: white; border: none; border-radius: 12px; padding: 1rem 2rem; font-size: 1rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; cursor: pointer; transition: all 0.3s ease; display: inline-flex; align-items: center; justify-content: center; gap: 0.8rem; box-shadow: 0 4px 16px rgba(5, 150, 105, 0.3); min-height: 60px;"
                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 24px rgba(5, 150, 105, 0.4)'"
                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 16px rgba(5, 150, 105, 0.3)'"
                    onmousedown="this.style.transform='translateY(0)'"
                    onmouseup="this.style.transform='translateY(-2px)'">
                <span>ENVIAR REPORTE</span>
                <i class="fas fa-paper-plane" style="font-size: 1.1rem;"></i>
            </button>
        </div>
    </div>

    <style>
        @media (max-width: 768px) {
            .container {
                padding-left: 0.5rem !important;
                padding-right: 0.5rem !important;
            }

            .col-12.col-md-6 {
                margin-bottom: 1.5rem;
            }
        }
    </style>
</div>

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/styleVigilancia.css') }}">
@endpush

@section('js')
<script>
    let inputCount = 1;
    function remArray(id){
        @this.call('deleteInput', id);
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

        divInput.appendChild(label);
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
<script>
    // Función que se ejecuta cuando el usuario hace clic en "ENVIAR"
    function activarGeolocalizacionYEnviar() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(success2, function(error) {
                console.error('Error obteniendo ubicación:', error);
                // Enviar sin ubicación si falla la geolocalización
                enviarNovedad();
            });
        } else {
            console.log('No tiene acceso a Ubicacion.');
            // Enviar sin ubicación si no hay soporte
            enviarNovedad();
        }
    }

    function success2(geoLocationPosition) {
        // console.log(geoLocationPosition.timestamp);
        let data = [
            geoLocationPosition.coords.latitude,
            geoLocationPosition.coords.longitude,
        ];

        // Usar la sintaxis moderna de Livewire para enviar las coordenadas
        @this.call('ubicacionAprox', data).then(() => {
            // Después de enviar la ubicación, ejecutar el envío
            enviarNovedad();
        }).catch((error) => {
            console.error('Error enviando ubicación:', error);
            // Enviar sin ubicación si falla
            enviarNovedad();
        });
    }

    function enviarNovedad() {
        @this.enviar();
    }
</script>
@endsection
