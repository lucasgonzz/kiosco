new Vue({
	el: "#lista-precios",
	created: function(){
		this.getArticulos(1);
		this.getMayoristas();
	},
	data: {
		articulos: [],
		articulo: {'id': '', 'act_fecha': true, 'name': '', 'cost': '', 'price': '', 'previus_price': '', 'stock': '', 'mayorista': '', 'codigo_barras': ''},
		mayoristas_lista: [],
		buscar: false,
		sale: {},
		ultimosActualizados: [],
		articulosEncontrados: '',
		pagination: {
            'total' : 0,
            'current_page' : 0,
            'per_page' : 0,
            'last_page' : 0,
            'from' : 0,
            'to' : 0,
		},
		offset: 2,
		searches: [],
		noEncontrado: '',
		article: {},
		ventasAnteriores: [],
		filtrar: 'no',
		filtro: 0,
		orden: 0,
		min: '',
		max: '',
		perPage: 15,
		current_page: 0,
		nombre: '',
		codigo: '',
		mayoristas: [],
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
		getArticulos: function(page){ 
			this.current_page = this.perPage;
			if(this.current_page != 0){
				axios.post('articles/index?page=' + page, {
					filtrar : this.filtrar,
					filtro : this.filtro,
					mayoristas: this.mayoristas,
					orden : this.orden,
					min : this.min,
					max : this.max,
					perPage : this.perPage,
				})
				.then( response => {
					// console.log(response.data);
					this.articulos = response.data.articles.data;
					this.pagination = response.data.pagination;
				})
				.catch( function(error) {
					console.log(error.response);
				});
			}else{
				$('#cargando-estados').show();
				console.log('sin paginacion: ' + this.filtrar);
				axios.post('articles/index', {
					filtrar : this.filtrar,
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
		showVentasAnteriores: function(articulo){
			console.log(articulo.sales);
			this.article = articulo;
			this.ventasAnteriores = articulo.sales;
			$('#ventasAnteriores').modal('show');
		},
		getUltimosActualizados: function(){
			axios.get('articles/ultimosActualizados')
			.then( response => {
				console.log(response.data);
				this.ultimosActualizados = response.data;
				$('#ultimosActualizados').modal('show');
			})
			.catch( error => {
				console.log(error.response);
			});
		},
		getSearches: function(){
			axios.get('searches')
			.then( response => {
				this.searches = response.data;
				$('#busquedas').modal('show');
			})
			.catch( error => {
				console.log(error.response);
				location.reload();
			});
		},
		searchUltimosAtcualizados: function(codigo_barras){
			axios.post('articles/buscar/'+codigo_barras)
			.then( response => {
				if(response.data.filtrado != 'no'){
					if(response.data.filtrado != 'codigo de barras'){
						this.articulosEncontrados = response.data.cantidad;
					}
					this.articulos = response.data.articles;
					// this.filtrado = 'codigo de barras';
					this.buscar = "";	
					$("#buscar").focus();
					$('#ultimosActualizados').modal('hide');			
				}else{
					// this.filtrado = '';
					this.articulosEncontrados = '';
					this.noEncontrado = 'No se encontraron resultados para ' + this.buscar;				
				}
			}).catch( error => {
				console.log(error.response);
			});
		},
		search: function(){
			$('#cargando-estados').show();
			axios.post('articles/buscar', {
				name : this.nombre,
				code : this.codigo,
				mayoristas : this.mayoristas,
			})
			.then( response => {
				// console.log(response.data);
				this.articulos = response.data;
				this.nombre = '';
				this.codigo = '';
				this.mayoristas = [];
				this.current_page = 0;
				// $("#buscar").focus();
			}).catch( error => {
				console.log(error.response);
			})
			.then(function(){
				$('#cargando-estados').hide();
			});
		},

		// Funcion para buscar solo con el codigo, en ultimas busquedas y ultimos actualizados
		searchWithCode: function(article){
			$('#cargando-estados').show();
			axios.post('articles/buscar', {
				code: article.codigo_barras
			})
			.then( response => {
				// console.log(response.data);
				$('#cargando-estados').show();
				this.articulos = response.data;
				$('#busquedas').modal('hide');
				$('#ultimosActualizados').modal('hide');
				this.current_page = 0;
			}).catch( error => {
				console.log(error.response);
			})
			.then(function(){
				$('#cargando-estados').hide();
			});
		},
		changePage: function(page){
			this.pagination.current_page = page;
			this.getArticulos(page);
		},
		getMayoristas: function(){
			axios.get('mayoristas').then( response => {
				this.mayoristas_lista = response.data;
			}).catch( error => {
				console.log(error.response);
			});
		},
		deleteArticulo: function(articulo){
			if(confirm('Seguro que quieres eliminar ' + articulo.name + "?")){
				axios.delete('articles/'+articulo.id).then( response => {
					this.getArticulos();
					toastr.success('Se elimino con exito ' + articulo.name);
				}).catch( error => {
					console.log(error.response);
				});
			}
		},
		updateArticulo: function(articulo){
			console.log(articulo);
			axios.put('articles/'+articulo.id, articulo)
			.then( response => {
				this.getArticulos();
				$('#modal').modal('hide');
				toastr.success(articulo.name + ' se actualizo con exito');
				this.articulo = {'id': '', 'act_fecha': true, 'name': '', 'cost': '', 'price': '', 'stock': '', 'mayorista': '', 'codigo_barras': ''};
			})
			.catch( error => {
				console.log(error.response);
			});
		},
		editArticulo: function(articulo){
			this.articulo.id = articulo.id;
			this.articulo.codigo_barras = articulo.codigo_barras;
			this.articulo.name = articulo.name;
			this.articulo.cost = articulo.cost;
			this.articulo.price = articulo.price;
			this.articulo.stock = articulo.stock;
			this.articulo.mayorista = articulo.mayorista;
			$("#modal").modal('show');
		}
	}
	// },
	// computed: {
	// 	buscarArticulos: function(){
	// 		return this.articulos.filter((item) => {
	// 			return item.name.toLowerCase().match(this.buscar) ||
	// 			// item.mayorista.includes(this.buscar);
	// 			item.codigo_barras.match(this.buscar);
	// 			// item.price.includes(this.buscar) ||
	// 		});
	// 	}
	// }
});
