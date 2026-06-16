let inputCount = 1;

function CARGAFOTO(inputId) {
    const inputElement = document.getElementById(inputId);
    const file = inputElement.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            const thumbnailContainerId = `thumbnailContainer${inputId.replace('fileInput', '')}`;
            createThumbnail(e.target.result, thumbnailContainerId);
            showDeleteButton(inputId);
            createNewFileInput();
        }
        reader.readAsDataURL(file);
    }
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
    input.accept = 'image/*';
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
    button.setAttribute('onclick', `deleteInput('${divRow.id}')`);

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
