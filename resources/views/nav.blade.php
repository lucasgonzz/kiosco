<nav class="navbar navbar-expand-lg">
	<button class="navbar-toggler btn-menu" type="button" id="btn-menu" data-toggle="collapse" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
		<i class="fas fa-bars"></i>
	</button>
	<div class="menu d-none d-lg-block" id="navbarNav">
		<ul class="navbar-nav">
			<li class="nav-item">
				<a class="nav-link {{ active('venta') }}" href="{{ route('venta') }}"><i class="fas fa-tags fa-xs m-r-5"></i>Venta</a>
			</li>
			<li class="nav-item">
				<a class="nav-link {{ active('ingresar') }}" href="{{ route('ingresar') }}"><i class="fas fa-external-link-alt fa-xs m-r-5"></i>Ingresar</a>
			</li>
			<li class="nav-item">
				<a class="nav-link {{ active('lista-precios') }}" href="{{ route('lista-precios') }}"><i class="fas fa-list-ol m-r-5"></i>Lista de precios</a>
			</li>
			<li class="nav-item">
				<a class="nav-link {{ active('resumen-ventas/hoy') }}" href="{{ route('resumen-ventas-hoy') }}"><i class="fas fa-key m-r-5 fa-xs"></i>Resumen de ventas</a>
			</li>
			<li class="nav-item">
				<a class="nav-link {{ active('codigos-de-barras') }}" href="{{ route('codigos-de-barras') }}"><i class="fas fa-barcode m-r-5"></i>Codigos de barra</a>
			</li>
			<li class="nav-item">
				<a class="nav-link btn-salir" onclick="event.preventDefault();
	                     document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt m-r-5"></i>Salir</a>
			    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
			        @csrf
			    </form>
			</li>
		</ul>	
	</div>
</nav>
<div class="menu-movil d-block d-lg-none">
	<div class="contenido">
		<ul class="nav flex-column">
		  <li class="nav-item">
		    <a class="nav-link {{ active('venta') }}" href="{{ route('venta') }}"><i class="fas fa-tags fa-xs m-r-5"></i>Venta</a>
		  </li>
		  <li class="nav-item">
		    <a class="nav-link {{ active('ingresar') }}" href="{{ route('ingresar') }}"><i class="fas fa-external-link-alt fa-xs m-r-5"></i>Ingresar</a>
		  </li>
		  <li class="nav-item">
		    <a class="nav-link {{ active('lista-precios') }}" href="{{ route('lista-precios') }}"><i class="fas fa-list-ol m-r-5"></i>Lista de precios</a>
		  </li>
		  <li class="nav-item">
		    <a class="nav-link {{ active('resumen-ventas') }}" href="{{ route('resumen-ventas-hoy') }}"><i class="fas fa-key m-r-5 fa-xs"></i>Resumen de ventas</a>
		  </li>
		  <li class="nav-item">
		    <a class="nav-link {{ active('codigos-de-barras') }}" href="{{ route('codigos-de-barras') }}"><i class="fas fa-barcode m-r-5"></i>Codigos de barra</a>
		  </li>
		  <li class="nav-item">
		  	<a class="nav-link" onclick="event.preventDefault();
	                     document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt m-r-5"></i>Salir</a>
		    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
		        @csrf
		    </form>
		  </li>
		</ul>
	</div>
</div>