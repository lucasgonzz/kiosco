@extends('app')
@section('content')
<div id="lista-precios">
	<div class="row m-t-20">
		<div class="col-12">
			@include('main.includes.lista-nav')
		</div>
	</div>
	
	<hr class="m-t-5 m-b-5">
	<div class="row m-t-20">
		<div class="col">
			<p>@{{ pagination.total }} articulos registrados</p>
		</div>
	</div>
	<div class="row m-t-5">
		<div class="col-6 col-lg-2 m-t-5">
			<button type="button" class="btn btn-primary d-none d-lg-block" v-on:click="getSearches()">
				Busquedas de hoy
			</button>
			<button type="button" class="btn btn-primary btn-lg btn-block d-lg-none" v-on:click="getSearches()">
				Busquedas de hoy
			</button>
		</div>
		<div class="col-6 col-lg-2 m-t-5">
			<button type="button" class="btn btn-primary d-none d-lg-block" v-on:click="getUltimosActualizados()">
				Ultimos actualizados
			</button>
			<button type="button" class="btn btn-primary btn-lg btn-block d-lg-none" v-on:click="getUltimosActualizados()">
				Ultimos actualizados
			</button>
			@include('main.modals.busquedas')
			@include('main.modals.ultimosActualizados')
		</div>
	</div>
	<div class="row m-t-10 p-r">
		<div class="col">

		@if($agent->isMobile())
			@include('main.includes.articleComponent')
		@else
			<table class="table table-striped table-hover table-sm m-t-20">
				<thead class="thead-dark">
					<tr>
						<th scope="col">ID</th>
						<th scope="col">Codigo</th>
						<th scope="col">Ingreso</th>
						<th scope="col">Nombre</th>
						<th scope="col">Mayorista</th>
						<th class="col-precio">Precio</th>
						<th scope="col">Costo</th>
						<th scope="col">Ult. Act</th>
						<th scope="col">Precio anterior</th>
						<th scope="col">Stock</th>
						<th scope="col" colspan="3" class="text-center">Opciones</th>
					</tr>
				</thead>
				<tbody>						
					<tr v-for="articulo in articulos" v-bind:class="articulo.style">
						<td>@{{ articulo.id }}</td>
						<td>@{{ articulo.codigo_barras }}</td>
						<td>@{{ articulo.creado }}</td>
						<td>@{{ articulo.name }}</td>
						<td>@{{ articulo.mayorista }}</td>
						<td class="precio">$@{{ articulo.price }}</td>
						<td>@{{ articulo.cost }}</td>
						<td>@{{ articulo.actualizado }}</td>
						<td>@{{ articulo.previus_price!=0 ? articulo.previus_price : ''}}</td>
						<td>@{{ articulo.stock }}</td>
						<td><a href="#" v-if="articulo.sales.length" class="btn btn-primary" v-on:click.prevent="showVentasAnteriores(articulo)"><i class="fas fa-shopping-cart m-r-5"></i>Ventas</a></td>
						<td><a href="#" class="btn btn-primary" v-on:click.prevent="editArticulo(articulo)"><i class="far fa-edit m-r-5"></i>Actualizar</a></td>
						<td><a href="#" class="btn btn-danger" v-on:click.prevent="deleteArticulo(articulo)"><i class="fas fa-trash m-r-5"></i>Borrar</a></td>
					</tr>
				</tbody>
			</table>
		@endif
			
		</div>
		<div class="spinner-border text-primary" id="cargando-estados" role="status">
		  	<span class="sr-only">Cargando...</span>
		</div>
	</div>
	<div v-show="current_page != 0">
		@include('main.modals.pagination')
	</div>
	
	<!-- <button class="scrollup">Arriba</button> -->
	@include('main.modals.editarArticulo')
	@include('main.modals.ventasAnteriores')
</div>
@endsection

@section('scripts')
<script>
$('#cargando-estados').hide();

new Vue({
	el: "#lista-precios",
	created: function(){
		this.getArticulos(1);
		this.getMayoristas();
	},
	data: {
		articulos: [],
		articulo: {'id': '', 'act_fecha': true, 'name': '', 'cost': '', 'price': '', 'previus_price': '', 'stock': '', 'mayorista': '', 'codigo_barras': ''},
		mayoristas_lista: [],
		buscar: false,
		sale: {},
		ultimosActualizados: [],
		articulosEncontrados: '',
		pagination: {
            'total' : 0,
            'current_page' : 0,
            'per_page' : 0,
            'last_page' : 0,
            'from' : 0,
            'to' : 0,
		},
		offset: 2,
		searches: [],
		noEncontrado: '',
		article: {},
		ventasAnteriores: [],
		filtrar: 'no',
		filtro: 0,
		orden: 0,
		min: '',
		max: '',
		perPage: 15,
		current_page: 0,
		busqueda: '',
		mayoristas: [],
	},
	computed: {
		isActived: function(){
			return this.pagination.current_page;
		},
		pagesNumber: function(){

			if(!this.pagination.to){
				return [];
			}

			var from = this.pagination.current_page - this.offset;
			
			if(from < 1){
				from = 1;
			}

			var to = from + (2 * this.offset);
			if(to >= this.pagination.last_page){
				to = this.pagination.last_page;
			}

			var pagesArray = [];
			while(from <= to){
				pagesArray.push(from);
				from++;
			}
			return pagesArray;
		}
	},
	methods: {
		getArticulos: function(page){ 
			this.current_page = this.perPage;
			if(this.current_page != 0){
				axios.post('articles/index?page=' + page, {
					filtrar : this.filtrar,
					filtro : this.filtro,
					mayoristas: this.mayoristas,
					orden : this.orden,
					min : this.min,
					max : this.max,
					perPage : this.perPage,
				})
				.then( response => {
					console.log(response.data);
					this.articulos = response.data.articles.data;
					this.pagination = response.data.pagination;
				})
				.catch( function(error) {
					console.log(error.response);
				});
			}else{
				$('#cargando-estados').show();
				console.log('sin paginacion: ' + this.filtrar);
				axios.post('articles/index', {
					filtrar : this.filtrar,
					filtro : this.filtro,
					mayoristas: this.mayoristas,
					orden : this.orden,
					min : this.min,
					max : this.max,
					perPage : this.perPage,
				})
				.then( response => {
					console.log(response.data);
					this.articulos = response.data;
				})
				.catch( function(error) {
					console.log(error.response);
				})
				.then(function(){
					console.log('termino');
					$('#cargando-estados').hide();
				});
			}
			
		},
		showVentasAnteriores: function(articulo){
			console.log(articulo.sales);
			this.article = articulo;
			this.ventasAnteriores = articulo.sales;
			$('#ventasAnteriores').modal('show');
		},
		getUltimosActualizados: function(){
			axios.get('articles/ultimosActualizados')
			.then( response => {
				console.log(response.data);
				this.ultimosActualizados = response.data;
				$('#ultimosActualizados').modal('show');
			})
			.catch( error => {
				console.log(error.response);
			});
		},
		getSearches: function(){
			axios.get('searches')
			.then( response => {
				this.searches = response.data;
				$('#busquedas').modal('show');
			})
			.catch( error => {
				console.log(error.response);
				location.reload();
			});
		},
		searchUltimosAtcualizados: function(codigo_barras){
			axios.post('articles/buscar/'+codigo_barras)
			.then( response => {
				if(response.data.filtrado != 'no'){
					if(response.data.filtrado != 'codigo de barras'){
						this.articulosEncontrados = response.data.cantidad;
					}
					this.articulos = response.data.articles;
					// this.filtrado = 'codigo de barras';
					this.buscar = "";	
					$("#buscar").focus();
					$('#ultimosActualizados').modal('hide');			
				}else{
					// this.filtrado = '';
					this.articulosEncontrados = '';
					this.noEncontrado = 'No se encontraron resultados para ' + this.buscar;				
				}
			}).catch( error => {
				console.log(error.response);
			});
		},
		search: function(){
			$('#cargando-estados').show();
			axios.get('articles/buscar/' + this.busqueda)
			.then( response => {
				this.articulos = response.data;
				this.current_page = 0;
			}).catch( error => {
				console.log(error.response);
			})
			.then(function(){
				$('#cargando-estados').hide();
			});
		},

		// Funcion para buscar solo con el codigo, en ultimas busquedas y ultimos actualizados
		searchWithCode: function(article){
			$('#cargando-estados').show();
			axios.post('articles/buscar', {
				code: article.codigo_barras
			})
			.then( response => {
				// console.log(response.data);
				$('#cargando-estados').show();
				this.articulos = response.data;
				$('#busquedas').modal('hide');
				$('#ultimosActualizados').modal('hide');
				this.current_page = 0;
			}).catch( error => {
				console.log(error.response);
			})
			.then(function(){
				$('#cargando-estados').hide();
			});
		},
		changePage: function(page){
			this.pagination.current_page = page;
			this.getArticulos(page);
		},
		getMayoristas: function(){
			axios.get('mayoristas').then( response => {
				this.mayoristas_lista = response.data;
			}).catch( error => {
				console.log(error.response);
			});
		},
		deleteArticulo: function(articulo){
			if(confirm('Seguro que quieres eliminar ' + articulo.name + "?")){
				axios.delete('articles/'+articulo.id).then( response => {
					this.getArticulos();
					toastr.success('Se elimino con exito ' + articulo.name);
				}).catch( error => {
					console.log(error.response);
				});
			}
		},
		updateArticulo: function(articulo){
			console.log(articulo);
			axios.put('articles/'+articulo.id, articulo)
			.then( response => {
				this.getArticulos();
				$('#modal').modal('hide');
				toastr.success(articulo.name + ' se actualizo con exito');
				this.articulo = {'id': '', 'act_fecha': true, 'name': '', 'cost': '', 'price': '', 'stock': '', 'mayorista': '', 'codigo_barras': ''};
			})
			.catch( error => {
				console.log(error.response);
			});
		},
		editArticulo: function(articulo){
			this.articulo.id = articulo.id;
			this.articulo.codigo_barras = articulo.codigo_barras;
			this.articulo.name = articulo.name;
			this.articulo.cost = articulo.cost;
			this.articulo.price = articulo.price;
			this.articulo.stock = articulo.stock;
			this.articulo.mayorista = articulo.mayorista;
			$("#modal").modal('show');
		}
	}
});

</script>

@endsection
