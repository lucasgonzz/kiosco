@extends('app')
@section('content')
<div class="cont m-t-30">
    <div id="nuevo-articulo">
		<div class="row">
			<div class="col-12 col-md-6 offset-md-3">	
				<form>
					{{ csrf_field() }}
					<div class="form-group">
						<label for="codigo_barras">Codigo de Barras</label>
						<input type="text" id="codigo_barra" name="codigo_barras" class="form-control focus-red" v-model="codigo_barra" v-on:change="isRegister()" autofocus>
					</div>
					<div class="row m-b-5">
						<div class="col">
							<label for="cost">Costo</label>
							<input type="text" name="cost" class="form-control focus-red" v-model="cost">
						</div>
						<div class="col">
							<label for="price">Precio</label>
							<input type="text" name="price" class="form-control focus-red" v-model="price">
						</div>
					</div>
					<div class="form-group">
						<label for="name">Nombre</label>
						<input type="text" name="name" class="form-control focus-red" v-model="name">
					</div>
					<div class="form-group">
						<label for="created_at">Fecha</label>
						<input type="date" name="created_at" class="form-control focus-red" v-model="created_at">
					</div>
					<div class="row m-b-15">
						<div class="col">
							<label for="mayorista">Mayorista</label>
							<select class="form-control focus-red" name="mayorista" v-model="mayorista">
								<option v-for="mayorista in mayoristas">@{{ mayorista.name }}</option>
							</select>
							<a href="#" v-on:click.prevent="addMayorista()">Añadir mayorista</a>
						</div>
						<div class="col">
							<label for="stock">Cantidad</label>
							<input type="text" name="stock" class="form-control focus-red" v-model="stock">
						</div>
					</div>
					<div class="form-group">
						<button type="button" v-on:click="newArticle()" class="btn btn-primary btn-lg btn-block focus-red">Guardar</button>
					</div> 
				</form>		
			</div>
		</div>
	@include('main.modals.nuevoMayorista')
	@include('main.modals.editarArticulo')
    </div>
</div>
@endsection

@section('scripts')
<script>
	new Vue({
	el: "#nuevo-articulo",
	created: function(){
		this.getMayoristas();
		this.getCB();
	},
	data: {
		codigo_barra: '',
		name: '',
		cost: '',
		price: '',
		created_at: new Date().toISOString().slice(0,10),
		mayorista: '',
		stock: '',
		mayoristas: [],
		codigosBarras: [],
		articulo: {'id': '', 'act_fecha': true, 'name': '', 'cost': '', 'price': '', 'stock': '', 'mayorista': '', 'codigo_barras': '', 'creado': '', 'actualizado': ''},
	},
	methods: {
		addMayorista: function(){
			$("#nuevo-mayorista").modal("show");
		},
		saveMayorista: function(){
			axios.post('mayoristas', {
				'name' : this.mayorista
			})
			.then( response => {
				$('#nuevo-mayorista').modal("hide");
				this.getMayoristas();
				toastr.success('Mayorista añadido con exito');
			})
			.catch( error => {
				console.log(error.response);
			})
		},
		getMayoristas: function(){
			axios.get('mayoristas')
			.then( response => {
				this.mayoristas = response.data;
			})
			.catch( error => {
				location.reload();
			});
		},
		getCB: function(){
			axios.get('cargar-codigos-barras')
			.then( response => {
				this.codigosBarras = response.data;
				$('#codigo_barra').focus();
			})
			.catch( error => {
				location.reload();
			});
		},
		isRegister: function(){
			if(this.codigosBarras.includes(this.codigo_barra)){
				axios.get('articles/'+this.codigo_barra)
				.then( response => {
					let article = response.data;
					this.articulo.id = article.id;
					this.articulo.creado = article.creado + ' hace ' + article.created_diff;
					this.articulo.actualizado = article.actualizado + ' hace ' + article.updated_diff;
					this.articulo.codigo_barras = article.codigo_barras;
					this.articulo.name = article.name;
					this.articulo.cost = article.cost;
					this.articulo.price = article.price;
					this.articulo.stock = article.stock;
					this.articulo.mayorista = article.mayorista;
					$("#modal").modal('show');
					$("#costo").focus();
				})
				.catch( error => {
					console.log(error.response);
				});	
			}
		},
		newArticle: function(){
			axios.post('articles', {
				codigo_barras : this.codigo_barra,
				name		 : this.name,
				cost		 : this.cost,
				price		 : this.price,
				created_at	 : this.created_at,
				mayorista	 : this.mayorista,
				stock		 : this.stock,
				act_fecha	 : this.act_fecha,
			}).then( response => {
				if(response.data=="exito"){
					toastr.success('Articulo guardado correctamente');
					this.codigo_barra="";
					this.name="";
					this.cost="";
					this.price="";
					this.stock="";
					$("#codigo_barra").focus();
				}else{
					toastr.error('Error al guardar articulo');
				}
			}).catch( error => {
				console.log(error.response.data);
			});
		},
		updateArticulo: function(articulo){
			axios.put('articles/'+articulo.id, this.articulo)
			.then( response => {
				$('#modal').modal('hide');
				toastr.success(articulo.name + ' se actualizo con exito');
				this.articulo = {'id': '', 'name': '', 'cost': '', 'price': '', 'stock': '', 'mayorista': '', 'codigo_barras': ''};
				this.codigo_barra = '';
				console.log(response.data);
				this.codigo_barra = '';
				$("#codigo_barra").focus();
			})
			.catch( error => {
				console.log(error.response.data);
			});
		},
	},
	computed: {
		ingresarMayorista: function(){
			return this.mayoristas.filter((item) => {
				return item.name.toLowerCase().match(this.mayorista);
			});
		}
	}
});

</script>
@endsection