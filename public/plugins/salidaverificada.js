document.addEventListener("livewire:load", function () {

    let cambiosPendientes = false;
    let usuarioInteractuo = false;

    window.addEventListener("click", () => usuarioInteractuo = true, { once: true });
    window.addEventListener("keydown", () => usuarioInteractuo = true, { once: true });

    document.addEventListener("input", function () {
        cambiosPendientes = true;
    });

    Livewire.on('guardado', () => {
        cambiosPendientes = false;
    });

    window.addEventListener("beforeunload", function (e) {
        if (!usuarioInteractuo) return;
        if (!cambiosPendientes) return;

        e.preventDefault();
        e.returnValue = "";
    });
});