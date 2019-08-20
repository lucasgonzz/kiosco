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
