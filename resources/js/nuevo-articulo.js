new Vue({
	el: "#nuevo-articulo",
	created: function(){
		this.getMayoristas();
		this.getCB();
	},
	data: {
		codigo_barra: '',
		name: '',
		cost: '',
		price: '',
		created_at: new Date().toISOString().slice(0,10),
		mayorista: '',
		stock: '',
		mayoristas: [],
		codigosBarras: [],
		articulo: {'id': '', 'act_fecha': true, 'name': '', 'cost': '', 'price': '', 'stock': '', 'mayorista': '', 'codigo_barras': ''},
	},
	methods: {
		addMayorista: function(){
			$("#nuevo-mayorista").modal("show");
		},
		saveMayorista: function(){
			axios.post('mayoristas', {
				'name' : this.mayorista
			})
			.then( response => {
				$('#nuevo-mayorista').modal("hide");
				this.getMayoristas();
				toastr.success('Mayorista añadido con exito');
			})
			.catch( error => {
				console.log(error.response);
			})
		},
		getMayoristas: function(){
			axios.get('mayoristas')
			.then( response => {
				this.mayoristas = response.data;
			})
			.catch( error => {
				location.reload();
			});
		},
		getCB: function(){
			axios.get('cargar-codigos-barras')
			.then( response => {
				this.codigosBarras = response.data;
				$('#codigo_barra').focus();
			})
			.catch( error => {
				location.reload();
			});
		},
		isRegister: function(){
			if(this.codigosBarras.includes(this.codigo_barra)){
				axios.get('articles/'+this.codigo_barra)
				.then( response => {
					let article = response.data;
					this.articulo.id = article.id;
					this.articulo.codigo_barras = article.codigo_barras;
					this.articulo.name = article.name;
					this.articulo.cost = article.cost;
					this.articulo.price = article.price;
					this.articulo.stock = article.stock;
					this.articulo.mayorista = article.mayorista;
					$("#modal").modal('show');
				})
				.catch( error => {
					console.log(error.response);
				});	
			}
		},
		newArticle: function(){
			axios.post('articles', {
				codigo_barras : this.codigo_barra,
				name		 : this.name,
				cost		 : this.cost,
				price		 : this.price,
				created_at	 : this.created_at,
				mayorista	 : this.mayorista,
				stock		 : this.stock,
				act_fecha	 : this.act_fecha,
			}).then( response => {
				// this.codigo_barra= '';
				// this.cost= '';
				// this.price= '';
				// this.created_at= new Date().toISOString().slice(0,10);
				// this.name= '';
				// this.mayorista= '';
				// this.stock= '';
				if(response.data=="exito"){
					toastr.success('Articulo guardado correctamente');
					this.codigo_barra="";
					this.name="";
					this.cost="";
					this.price="";
					this.stock="";
					$("#codigo_barra").focus();
				}else{
					toastr.error('Error al guardar articulo');
				}
			}).catch( error => {
				console.log(error.response.data);
			});
		},
		updateArticulo: function(articulo){
			axios.put('articles/'+articulo.id, this.articulo)
			.then( response => {
				$('#modal').modal('hide');
				toastr.success(articulo.name + ' se actualizo con exito');
				this.articulo = {'id': '', 'name': '', 'cost': '', 'price': '', 'stock': '', 'mayorista': '', 'codigo_barras': ''};
				this.codigo_barra = '';
				console.log(response.data);
				this.codigo_barra = '';
				$("#codigo_barra").focus();
			})
			.catch( error => {
				console.log(error.response.data);
			});
		},
	},
	computed: {
		ingresarMayorista: function(){
			return this.mayoristas.filter((item) => {
				return item.name.toLowerCase().match(this.mayorista);
			});
		}
	}
});
