@extends('app')
@section('content')
	<div id="resumen-ventas">
		<div class="row m-t-20 m-b-20">
			<div class="col">
				@include('main.includes.resumen-ventas-nav')
			</div>
		</div>
		<div class="row">
			<div class="col">
				<form class="form-inline" @submit.prevent="salesFrom">
					<div class="form-group">
						<label for="desde">Desde</label>
						<input v-model="desde" type="date" id="desde" class="form-control mx-sm-3">
					</div>
					<div class="form-group">
						<label for="hasta">Hasta</label>
						<input v-model="hasta" type="date" id="hasta" class="form-control mx-sm-3">
					</div>

					<button type="submit" class="btn btn-primary">Buscar</button>
				</form>
			</div>
		</div>
		<div class="row m-b-10 justify-content-between" v-show="sales.length">
			<div class="col-12 col-lg-4 align-self-center">
				<div class="tab-content" id="pills-tabContent">
					<div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
						<ul class="list-group list-group-horizontal">
							<li class="list-group-item">@{{ sales.length }} ventas</li>
							<li class="list-group-item">@{{ ventas_cont }} articulos vendidos</li>
							<li class="list-group-item">Resumen: $@{{ total }}</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="col-12 col-lg-3">
				<p class="h5">Ver</p>
				<div class="form-check form-check-inline">
					<b-radio type="radio" v-model="mostrar" name="solo-ventas" native-value="0" id="solo-ventas">Solo las ventas</b-radio>
				</div>
				<div class="form-check form-check-inline">
					<b-radio type="radio" v-model="mostrar" name="ventas-con-articulos" native-value="1" id="ventas-con-articulos">Las ventas y sus articulos</b-radio>
				</div>
			</div>
		</div>
		<div class="row" v-if="mostrar == 0">
			<div class="col">
				<div class="accordion" id="acordeon">
					<div class="card" v-for="(sale, index) in sales">
						<div class="card-header" v-bind:id="'heading' + index">
							<h2 class="mb-0">
								<button class="btn" type="button" data-toggle="collapse" v-bind:data-target="'#collapse' + index" v-bind:aria-expanded="index == 0 ? 'true' : 'false'" v-bind:aria-controls="'collapse' + index">
									<div class="media">
										<i class="far fa-clock fa-3x"></i>
										<div class="media-body m-l-5">
											<h3 class="mt-0 h5">@{{ sale.hora }}</h3>
											<p class="h6">$@{{ sale.total }}</p>
										</div>
									</div>
									
								</button>
							</h2>
						</div>

						<div v-bind:id="'collapse' + index" v-bind:class="index == 0 ? 'collapse show' : 'collapse'" :aria-labelledby="'heading' + index" data-parent="#acordeon">
							<div class="card-body">
								<div class="row">
									<div class="col-12 col-lg-6">
										<!-- <ul class="list-group" v-for="article in sale.articles">
											<li class="list-group-item">@{{ article.name }} | $@{{ article.price }}</li>
										</ul> -->
										<table class="table">
											<thead class="thead-dark">
												<tr>
													<th scope="col">Articulo</th>
													<th scope="col">Precio</th>
													<th scope="col">Ventas anteriores</th>
												</tr>
											</thead>
											<tbody>
												<tr v-for="article in sale.articles">
													<td>@{{ article.name }}</td>
													<td>$@{{ article.price }}</td>
													<td v-if="article.sales.length>1"><a href="#" class="btn btn-outline-primary" @click.prevent="showVentasAnteriores(article)">Ventas anteriores</a></td>
												</tr>
											</tbody>
										</table> 
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row" v-else>
			<div class="col">
				<table class="table">
					<thead class="thead-dark">
						<tr>
							<th scope="col">Hora</th>
							<th scope="col">Articulo</th>
							<th scope="col">Precio</th>
							<th scope="col">Opciones</th>
						</tr>
					</thead>
					<tbody>
						<template v-for="sale in sales">
							<tr v-for="article in sale.articles">
								<td>@{{ sale.hora }}</td>
								<td>@{{ article.name }}</td>
								<td>$@{{ article.price }}</td>
								<td v-if="article.sales.length>1"><a href="#" class="btn btn-outline-primary" @click.prevent="showVentasAnteriores(article)">Ventas anteriores</a></td>
							</tr>
						</template>
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
	data: {
		sales: [],
		sale: {},
		article: {},
		mostrar: 'ventas-hoy',
		desde: null,
		hasta: null,
		total: 0,
		ventasAnteriores: [],
		mostrar: 0,
		desde: '',
		hasta: '',
	},
	methods: {
		showVentasAnteriores: function(articulo){
			this.article = articulo;
			this.ventasAnteriores = articulo.sales;
			$('#ventasAnteriores').modal('show');
		},
		salesFrom: function(){
			axios.post('sales/salesFromDate', {
				desde : this.desde,
				hasta : this.hasta,
			})
			.then( response => {
				this.sales = response.data;
				this.total = 0;
				this.ventas_cont = 0;
				for(let i in this.sales){
					this.sales[i].total = 0;
					for(let j in this.sales[i].articles){
						this.total += this.sales[i].articles[j].price;
						this.sales[i].total += this.sales[i].articles[j].price;
						this.ventas_cont ++;
					}
				}
			}).catch( error => {
				console.log(error.response);
			});
		},
		getSales: function(){
			axios.get('sales/today')
			.then( response => { 
				this.sales = response.data;
				this.total = 0;
				this.ventas_cont = 0;
				for(let i in this.sales){
					this.sales[i].total = 0;
					for(let j in this.sales[i].articles){
						this.total += this.sales[i].articles[j].price;
						this.sales[i].total += this.sales[i].articles[j].price;
						this.ventas_cont ++;
					}
				}
			})
			.catch( error => {
				console.log(error.response);
			})
		},
		getSalesMorning: function(){
			axios.get('sales/today/morning')
			.then( response => {
				console.log(response.data);
				this.sales = response.data;
				this.total = 0;
				for(let i in this.sales){
					for(let j in this.sales[i].articles){
						this.total += this.sales[i].articles[j].price;
					}
				}
				for(let i in this.sales){
					this.sales[i].total = 0;
					for(let j in this.sales[i].articles){
						this.sales[i].total += this.sales[i].articles[j].price;
					}
				}
			})
			.catch( error => {
				console.log(error.response);
			})
		},
		getSalesAfternoon: function(){
			axios.get('sales/today/afternoon')
			.then( response => {
				console.log(response.data);
				this.sales = response.data;
				this.total = 0;
				for(let i in this.sales){
					for(let j in this.sales[i].articles){
						this.total += this.sales[i].articles[j].price;
					}
				}
				for(let i in this.sales){
					this.sales[i].total = 0;
					for(let j in this.sales[i].articles){
						this.sales[i].total += this.sales[i].articles[j].price;
					}
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
