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
				<ul class="nav nav-tabs mb-3" id="pills-tab" role="tablist">
					<li class="nav-item m-l-0">
						<a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true" @click="getSales">Todas</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-profile" aria-selected="false" @click="getSalesMorning">De mañana</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-contact" aria-selected="false" @click="getSalesAfternoon">De tarde</a>
					</li>
				</ul>
			</div>
		</div>
		@include('main.includes.resumen-ventas-info')
		<hr style="background-color: #A8A8A8">
		@include('main.includes.resumen-ventas-show-sales')		
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
		total: 0,
		ventasAnteriores: [],
		mostrar: 0,
		ventas_cont: 0,
	},
	methods: {
		showVentasAnteriores: function(articulo){
			this.article = articulo;
			this.ventasAnteriores = articulo.sales;
			$('#ventasAnteriores').modal('show');
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
		getSalesAfternoon: function(){
			axios.get('sales/today/afternoon')
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
