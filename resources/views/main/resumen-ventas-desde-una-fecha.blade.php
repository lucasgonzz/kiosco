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
		@include('main.includes.resumen-ventas-info')
		
		@include('main.includes.resumen-ventas-show-sales')
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
