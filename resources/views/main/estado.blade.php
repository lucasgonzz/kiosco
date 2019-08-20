@extends('app')
@section('content')
<div id="estados">
	<div class="row m-t-30">
		<div class="col-12">
			<form>
				<div class="form-row align-items-center">
					<div class="col-12 m-xs-b-20 m-md-b-0 col-lg-2">
						<label for="filtro">Mostrar elementos que</label>
						<select v-model="filtro" name="filto" id="filtro" class="form-control">
							<!-- <option value="0">Desplegar</option> -->
							<option value="0">Tengan 1 en stock</option>
							<option value="1">No tengan stock</option>
							<option value="2">Esten desactualizados</option>
						</select>
					</div>
					<div class="col-12 m-xs-b-20 m-md-b-0 col-lg-2">
						<label for="orden">Ordenar por</label>
						<select v-model="orden" name=orden" id="orden" class="form-control">
							<option value="0" selected>Mas viejos</option>
							<option value="1" selected>Mas nuevos</option>
							<option value="2" selected>Menor precio</option>
							<option value="3" selected>Mayor precio</option>
							<option value="4" selected>Mayorista</option>
						</select>
					</div>
					<div class="col-12 m-xs-b-20 m-md-b-0 col-lg-2">
						<label for="orden">Precio entre</label>
						<div>
							<input v-model="min" class="form-control input-precio" type="text" placeholder="Min"> - <input v-model="max" class="form-control input-precio" type="text" placeholder="Max">
						</div>
					</div>
					<div class="col-12 m-xs-b-20 m-md-b-0 col-lg-2 m-lg-t-20">
						<label for="orden m-b-10">Elementos por página</label>
						<input type="number" min="0" v-model="perPage" class="form-control">
						<small class="form-text text-muted">Deje 0 si quiere mostrar todos</small>
					</div>
					<div class="col-12 col-lg-2 m-lg-t-10">
						<button class="btn btn-primary d-none d-lg-block" @click.prevent="filtrado(1)">Mostrar</button>
						<button class="btn btn-primary btn-block d-lg-none" @click.prevent="filtrado(1)">Mostrar</button>
					</div>
				</div> <!--  -->
			</form>
		</div> <!-- .col -->
	</div> <!-- .row -->
	<div class="row m-t-20">
		<div class="col">
			<p v-if="current_page == 0 && articulos.length">@{{ articulos.length }} articulos encontrados</p>
			<p v-else>@{{ pagination.total }} articulos encontrados</p>
		</div>
	</div>
	<div class="row p-r">
		<div class="col">
			<table class="table table-striped table-hover table-sm m-t-20">
				<thead class="thead-dark">
					<tr>
						<th scope="col">Codigo</th>
						<th scope="col">Nombre</th>
						<th scope="col">Costo</th>
						<th scope="col">Precio</th>
						<th scope="col">Mayorista</th>
						<th scope="col">Ingreso</th>
						<th scope="col">Ult. Act</th>
						<th scope="col">Precio anterior</th>
						<th scope="col">Stock</th>
						<th scope="col" colspan="2" class="text-center">Opciones</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="articulo in articulos" v-bind:class="articulo.style">
						<td>@{{ articulo.codigo_barras }}</td>
						<td>@{{ articulo.name }}</td>
						<td>@{{ articulo.cost }}</td>
						<td>@{{ articulo.price }}</td>
						<td>@{{ articulo.mayorista }}</td>
						<td>@{{ articulo.creado }}</td>
						<td>@{{ articulo.actualizado }}</td>
						<td>@{{ articulo.previus_price }}</td>
						<td>@{{ articulo.stock }}</td>
						<td v-if="articulo.sales.length">
							<a href="#" @click.prevent="showVentasAnteriores(articulo)" class="btn btn-primary">Ventas</a>
						</td>
					</tr>
				</tbody>
			</table>
		</div> <!-- .col -->
		<div class="spinner-border text-primary" id="cargando-estados" role="status">
		  	<span class="sr-only">Cargando...</span>
		</div>
	</div><!-- .row -->
	@include('main.modals.ventasAnteriores')
	@include('main.modals.pagination')

</div>
@endsection