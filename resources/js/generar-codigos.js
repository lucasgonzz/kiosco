new Vue({
	el: "#generar-codigos",
	data: {
		codigo: '',
	},
	methods: {
		generarCodigo: function(){
			$('#imagen-codigo').attr('src', 'generar-codigo/' + this.codigo);
		}
	}
});