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
		<div class="tab-content" id="pills-tabContent">
			<div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
				<ul class="list-group list-group-horizontal">
					<li class="list-group-item">@{{ sales.length }} ventas</li>
					<li class="list-group-item">Resumen: $@{{ total }}</li>
				</ul>
			</div>
		</div>
		<div class="row">
			<div class="col">
				<div class="accordion" id="acordeon">
					<div class="card" v-for="(sale, index) in sales">
						<div class="card-header" v-bind:id="'heading' + index">
							<h2 class="mb-0">
								<button class="btn btn-link" type="button" data-toggle="collapse" v-bind:data-target="'#collapse' + index" v-bind:aria-expanded="index == 0 ? 'true' : 'false'" v-bind:aria-controls="'collapse' + index">
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
								@{{ index }}
							</div>
						</div>
					</div>



				</div>

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
				this.total = 0;
				// for(let i in this.sales){
				// 	this.total += this.sales[i].article.price;
				// }
				for(let i in this.sales){
					this.sales[i].total = 0;
					for(let j in this.sales[i].articles){
						this.sales[i].total += this.sales[i].articles[j].price;
					}
				}
				console.log('paso');
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
					this.total += this.sales[i].article.price;
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
