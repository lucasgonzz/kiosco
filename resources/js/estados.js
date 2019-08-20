$('#cargando-estados').hide();

new Vue({
	el: '#estados',
	created: function(){
		//this.filtrado();
	},
	data: {
		articulos: [],
		article: {},
		ventasAnteriores: [],
		sale: {},
		filtro: 0,
		orden: 0,
		min: '',
		max: '',
		perPage: 0,
		current_page: 0,
		pagination: {
            'total' : 0,
            'current_page' : 0,
            'per_page' : 0,
            'last_page' : 0,
            'from' : 0,
            'to' : 0,
		},
		offset: 2,
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
		filtrado: function(page){ 
			this.current_page = this.perPage;
			if(this.current_page != 0){
				axios.post('articles/estado?page=' + page, {
					filtro : this.filtro,
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
				axios.post('articles/estado', {
					filtro : this.filtro,
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
					$('#cargando-estados').hide();
				});
			}
			
		},
		changePage: function(page){
			this.pagination.current_page = page;
			this.filtrado(page);
		},
		showVentasAnteriores: function(articulo){
			this.article = articulo;
			this.ventasAnteriores = articulo.sales;
			$('#ventasAnteriores').modal('show');
		}
	}
});