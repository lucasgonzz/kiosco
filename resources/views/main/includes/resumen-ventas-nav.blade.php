<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link {{ active('resumen-ventas/hoy') }}" href="{{ route('resumen-ventas-hoy') }}">Ventas de hoy</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ active('resumen-ventas/desde-una-fecha') }}" href="{{ route('resumen-ventas-desde-una-fecha') }}">Desde una fecha</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#">Dias mas vendidos</a>
    </li>
</ul>