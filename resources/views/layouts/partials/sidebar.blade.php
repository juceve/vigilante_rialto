<div class="menu_section">
    <ul class="nav side-menu">
        <li>
            <a class="nav-link" href="{{route('home')}}">
                <i class="fa fa-home"></i>
                <span>INICIO</span></a>
        </li>
    </ul>
    <h3>REGISTROS</h3>
    <ul class="nav side-menu">
        <li>
            <a class="nav-link" href="{{route('customer.listadoresidencias')}}">
                <i class="fa fa-tags" aria-hidden="true"></i>
                <span>Residencias</span></a>
        </li>
        <li>
            <a class="nav-link" href="{{route('customer.listadopropietarios')}}">
                <i class="fa fa-book" aria-hidden="true"></i>
                <span>Propietarios</span></a>
        </li>
        {{-- <li>
            <a class="nav-link" href="{{route('customer.listadosolicitudes')}}">
                <i class="fa fa-check-square" aria-hidden="true"></i>
                <span>Solicitudes de Aprobación</span></a>
        </li> --}}
        <li>
            <a class="nav-link" href="{{route('customer.visitas')}}">
                <i class="fa fa-group"></i>
                <span>Visitas</span></a>
        </li>
        <li>
            <a class="nav-link" href="{{route('customer.novedades')}}">
                <i class="fa fa-newspaper-o"></i>
                <span>Novedades</span></a>
        </li>
        <li>
            <a class="nav-link" href="{{route('customer.rondas')}}">
                <i class="fa fa-street-view"></i>
                <span>Rondas</span></a>
        </li>
        {{-- <li>
            <a class="nav-link" href="{{route('customer.links')}}">
                <i class="fa fa-file-text-o"></i>
                <span>Airbnb</span></a>
        </li> --}}
    </ul>
    <br>
    <h3>DOCUMENTOS VINCULADOS</h3>
    <ul class="nav side-menu">
        <li>
            <a class="nav-link" href="{{route('customer.informes')}}">
                <i class="fa fa-file-text"></i>
                <span>Informes</span></a>
        </li>
        <li>
            <a class="nav-link" href="{{route('customer.cobros')}}">
                <i class="fa fa-money"></i>
                <span>Cobros</span></a>
        </li>
        <li>
            <a class="nav-link" href="{{route('customer.recibos')}}">
                <i class="fa fa-list-alt"></i>
                <span>Recibos</span></a>
        </li>
    </ul>
</div>
