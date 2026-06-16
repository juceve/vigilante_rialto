    $(document).ready(function () {
        // Al abrir cualquier colapso
        $('.collapse').on('show.bs.collapse', function () {
            // Buscar el ícono dentro del botón que apunta a este collapse
            var icon = $('button[data-target="#' + this.id + '"] i');
            icon.removeClass('fa-plus').addClass('fa-minus');
        });

        // Al cerrar cualquier colapso
        $('.collapse').on('hide.bs.collapse', function () {
            var icon = $('button[data-target="#' + this.id + '"] i');
            icon.removeClass('fa-minus').addClass('fa-plus');
        });
    });
