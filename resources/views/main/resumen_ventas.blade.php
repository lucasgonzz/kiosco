@extends('app')
@section('content')
	<div id="resumen-ventas">
		<div class="row m-t-20">
			<div class="col">
				@include('main.includes.resumen-ventas-nav')
			</div>
		</div>
		<!-- <div class="row m-t-20">
			<div class="col-12 col-md-6">
				<h2><strong>Mostrar las ventas de</strong></h2>
			</div>
		</div>
		<div class="row m-t-10">
			<div class="col-12 col-md-2">
	        	<b-radio type="radio" v-model="mostrar" id="ventas-hoy" native-value="ventas-hoy">Hoy</b-radio>
        	</div>
			<div class="col-12 col-md-6">
	        	<b-radio type="radio" v-model="mostrar" id="ventas-fecha" native-value="ventas-fecha">Desde una fecha en especifico</b-radio>
        	</div>
		</div>
		<div v-show="mostrar=='ventas-fecha'">
			<div class="row m-t-5">
				<div class="col-6 col-md-3 align-self-start">
					<label for="cost">Desde</label>
					<input type="date" v-model="desde" name="" id="" class="form-control">
				</div>
				<div class="col-6 col-md-3 align-self-start">
					<label for="price">Hasta</label>
					<input type="date" v-model="hasta" name="" id="" class="form-control">
				</div>
				<div class="col-12 col-md-2 align-self-center">
					<a href="#" class="btn btn-primary d-none d-md-inline" v-on:click.prevent="salesFrom()"><i class="fas fa-search m-r-5"></i>Buscar</a>
					<a href="#" class="btn btn-primary d-block d-md-none btn-block m-t-10" v-on:click.prevent="salesFrom()"><i class="fas fa-search m-r-5"></i>Buscar</a>
				</div>
			</div>
		</div> -->
		<div class="row justify-content-between">
			<div class="col-3 col-lg-3 align-self-center">
				<p><strong>@{{ sales.length }} ventas</strong></p>
			</div>
			<!-- <div class="col-3 col-lg-3 align-self-center">
				<a href="#" class="btn btn-primary">Dias mas vendidos</a>
			</div> -->
			<div class="col-9 col-lg-3">
				<table class="table table-striped table-hover table-sm m-t-20">
					<thead class="thead">
						<tr>
							<th>Costo</th>
							<th>Total</th>
							<th>Ganancia</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>@{{ costo }}</td>
							<td>@{{ total }}</td>
							<td>@{{ total-costo }}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		
		<div class="row">
			<div class="col">
				<table class="table table-striped table-hover table-sm m-t-20">
					<thead class="thead-dark">
						<div class="thead">
							<tr>
								<th>Fecha</th>
								<th>Hora</th>
								<th>Codigo de barras</th>
								<th>Articulo</th>
								<th>Costo</th>
								<th>Precio</th>
								<th>En Stock</th>
								<th colspan="2">Opciones</th>
							</tr>
						</div>
					</thead>
					<tbody>
						<tr v-for="sale in sales">
							<td>@{{ sale.creado }}</td>
							<td>@{{ sale.hora }}</td>
							<td>@{{ sale.article.codigo_barras }}</td>
							<td>@{{ sale.article.name }}</td>
							<td>$@{{ sale.article.cost }}</td>
							<td>$@{{ sale.article.price }}</td>
							<td>@{{ sale.article.stock }}</td>
							<td><a href="#" class="btn btn-danger" v-on:click.prevent="deleteSale(sale)"><i class="fas fa-trash m-r-5"></i></a></td>
							<td v-show="sale.article.sales.length>1"><a href="#" class="btn btn-outline-primary" v-on:click.prevent="showVentasAnteriores(sale.article.sales, sale)"><i class="fas fa-shopping-cart m-r-5"></i>Ventas anteriores</a></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		@include('main.modals.ventasAnteriores')
	</div>
@endsection

@section('scripts')
<script>
	new Vue({
	el: "#resumen-ventas",
	created: function(){
		this.getSales();
	},
	data: {
		sales: [],
		sale: {},
		article: {},
		mostrar: 'ventas-hoy',
		desde: null,
		hasta: null,
		costo: 0,
		total: 0,
		ventasAnteriores: [],
	},
	methods: {
		showVentasAnteriores: function(sales, sale){
			this.article = sale.article;
			this.sale = sale;
			this.ventasAnteriores = sales;
			$('#ventasAnteriores').modal('show');
		},
		salesFrom: function(){
			axios.post('sales/salesFromDate', {
				desde : this.desde,
				hasta : this.hasta,
			})
			.then( response => {
				console.log(response);
				this.sales = response.data;
				for(let i in this.sales){
					if(this.sales[i].article.cost!=null || this.sales[i].article.cost>0){
						this.costo += this.sales[i].article.cost;
					}else{

					}
					this.total += this.sales[i].article.price;
				}
			}).catch( error => {
				console.log(error.response);
			});
		},
		getSales: function(){
			axios.post('sales/today')
			.then( response => {
				console.log(response.data);
				this.sales = response.data;
				for(let i in this.sales){
					this.costo += this.sales[i].article.cost;
					this.total += this.sales[i].article.price;
				}
			})
			.catch( error => {
				console.log(error.response);
			})
		},
		deleteSale: function(sale){
			if(confirm("Seguro de eliminar la vente de " + sale.article.name + "?, se repondra la unidad en stock")){
				axios.delete('sales/' + sale.id)
				.then( response => {
					toastr.success('Se elimino con exito la venta de ' + sale.article.name);
					this.getSales();
					this.salesFromDate();
					for(let i in this.salesFromDateList){
						this.costo += this.salesFromDateList[i].article.cost;
						this.total += this.salesFromDateList[i].article.price;
					}
				}).catch( error => {
					console.log(error.response.data);
				});
			}
		}
	}
});

</script>
@endsection
