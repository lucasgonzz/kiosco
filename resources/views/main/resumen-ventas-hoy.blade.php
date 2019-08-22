@extends('app')
@section('content')
	<div id="resumen-ventas">
		<div class="row m-t-20">
			<div class="col">
				@include('main.includes.resumen-ventas-nav')
			</div>
		</div>
		<div class="row m-t-20">
			<div class="col">
				<ul class="nav nav-tabs mb-3" id="pills-tab" role="tablist">
					<li class="nav-item m-l-0">
						<a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Todas</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">De mañana</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">De tarde</a>
					</li>
				</ul>
			</div>
		</div>
		<div class="tab-content" id="pills-tabContent">
			<div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
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
			</div>
			<div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">...</div>
			<div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">...</div>
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
			axios.get('sales/today')
			.then( response => {
				console.log(response.data);
				this.sales = response.data;
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
