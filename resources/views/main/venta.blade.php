@extends('app')
@section('content')
	<div class="row justify-content-md-center m-t-75">
		<div class="col-12 col-md-6">
			<div id="venta">
				<div class="row m-b-15 align-items-center" v-show=" contado != 0 ">
					<div class="col">
						<table class="table">
							<thead class="thead-dark">
								<tr>
									<th scope="col">Nombre</th>
									<th scope="col">Precio</th>
									<th scope="col">Quedan</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="venta in ventas">
									<td>@{{ venta.name }}</td>
									<td>$@{{ venta.price }}</td>
									<td>@{{ venta.stock }}</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="row m-b-15" v-show=" contado != 0 ">
					<div class="col">
						<ul class="list-group list-group-horizontal">
							<li class="list-group-item">Contado: $@{{ contado }}</li>
							<li class="list-group-item">Tarjeta: $@{{ contado + (contado/100*18) }}</li>
						</ul>
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<div class="form-row align-items-center">
							<div class="col-10">
								<div class="input-group mb-2">
									<div class="input-group-prepend">
										<div class="input-group-text"><i class="fas fa-barcode"></i></div>
									</div>
									<input type="text" class="form-control focus-red" v-model="codigo_barras" placeholder="Codigo de barras" @keyup.enter="itemVentaCBarras">
								</div>
							</div>
							<div class="col-2">
								<button class="btn btn-primary mb-2 focus-red" @click="nuevaVenta">Venta</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('scripts')
<script>
	$("#codigo_barras").focus();
new Vue({
	el: "#venta",
	created: function(){
		this.cargarCodigosDisponibles();
		toastr.options = {
			"closeButton": false,
			"debug": false,
			"newestOnTop": false,
			"progressBar": false,
			"positionClass": "toast-bottom-right",
			"preventDuplicates": false,
			"onclick": null,
			"showDuration": "300",
			"hideDuration": "1000",
			"timeOut": "5000",
			"extendedTimeOut": "1000",
			"showEasing": "swing",
			"hideEasing": "linear",
			"showMethod": "fadeIn",
			"hideMethod": "fadeOut"
		}
	},
	data: {
		ventas: [],
		id_articles: [],
		venta_actual: {},
		codigo_barras: '',
		precio_suelto: '',
		codigos_barras_disponibles: [],
		error_cb: '',
		error_bajar: '',
		error_subir: '',
		bajar: 0,
		subir: 0,
		contado: 0,
	},
	methods: {
		itemVentaPrecioSuelto: function() {
			this.ventas.push({
				'price' : this.precio_suelto,
			});
			this.ventas_request.push({
				'article' : false,
				'price' : this.precio_suelto,
			});
			this.precio_suelto = '';
		},
		itemVentaCBarras: function() {
			if(this.codigo_barras!=''){
				if(this.codigos_barras_disponibles.includes(this.codigo_barras)){
					axios.get('sales/addItemByBCode/' + this.codigo_barras)
					.then( response => {
						let article = response.data;
						this.ventas.push({
							'codigo_barras' : this.codigo_barras,
							'name' : article.name,
							'price' : article.price,
							'stock' : article.stock - 1,
						});
						this.id_articles.push(article.id);
						this.contado += article.price;
						this.codigo_barras = '';
					})
					.catch( error => {
						console.log(error.response.data);
					});
				}else{
					toastr.error('Ingrese un codigo de barras disponible');
					this.codigo_barras = '';
				}
			}
		},
		nuevaVenta: function(){
			axios.post('sales', {
				ventas : this.id_articles
			})
			.then( response => {
				console.log(response.data);
				this.contado = 0;
			})
			.catch( error => {
				console.log(error.response.data);
			});

			// this.error_cb = "";
			// axios.post('sales', {
			// 	'codigo_barras' : this.codigo_barras
			// })
			// .then( response => {
			// 	this.bajar = this.ventas.length;
			// 	var article = response.data;
			// 	// console.log(article);
			// 	if(article.stock==1){
			// 		toastr.warning("Queda solo un 1 " + article.name);
			// 	}else if(article.stock==0){
			// 		toastr.error("Ya no queda ningun/a " + article.name);
			// 	}else if(article.old){
			// 		toastr.warning(article.name + " no esta actualizado");
			// 	}
			// 	var v=this.ventas.push({
			// 		'sale_id'	: article.sale_id, 
			// 		'name'	: article.name,
			// 		'price'	: article.price,
			// 		'stock'	: article.stock,
			// 	});
			// 	this.venta_actual = {
			// 		'sale_id': article.sale_id, 
			// 		'name': article.name, 
			// 		'price': article.price, 
			// 		'stock': article.stock
			// 	},
			// 	this.codigo_barras = '';
			// })
			// .catch( error => {
			// 	if (error.response) {
			//         console.log(error.response.data.message);
			//     }else{
			//     	console.log(error);
			//     }
			// });
		},
		bajarVenta: function(){
			if(this.bajar>0){
				this.bajar--;
				this.error_bajar = "";
				this.error_subir = "";
				var venta_anterior = this.ventas[this.bajar];
				this.venta_actual = {
					'sale_id': venta_anterior.sale_id,
					'name': venta_anterior.name,
					'price': venta_anterior.price,
					'stock': venta_anterior.stock,
				}
			}else{
				toastr.error('No hay ventas anteriores!!');
			}
		},
		subirVenta: function(){
			if(this.bajar < this.ventas.length-1){
				this.bajar++;
				this.error_bajar = "";
				this.error_subir = "";
				console.log(this.bajar);
				var venta_posterior = this.ventas[this.bajar];
				this.venta_actual = {
					'sale_id': venta_posterior.sale_id,
					'name': venta_posterior.name,
					'price': venta_posterior.price,
					'stock': venta_posterior.stock,
				}
			}else{
				toastr.error('Esta es tu ultima venta!!');
			}
		},
		eliminarVenta(){
			var article = this.venta_actual;
			var url = "sales/"+article.sale_id;
			axios.delete(url)
			.then( response => {
				this.ventas.splice(this.bajar, 1);
				alert("Se elimino la venta de " + article.name);
			}).catch( error => {
				console.log(error.response.data);
			});
		},
		cargarCodigosDisponibles: function(){
			axios.get('cargar-codigos-barras')
			.then( response => {
				this.codigos_barras_disponibles = response.data;
				$('#codigo_barras').focus();
			})
			.catch( error => {
				location.reload();
			});
		},
	}
});
</script>
@endsection