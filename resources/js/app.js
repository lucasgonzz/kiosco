
window.$ = window.jQuery = require('jquery')
require('./bootstrap');

window.Vue = require('vue');


import Buefy from 'buefy';
import 'buefy/dist/buefy.css';
Vue.use(Buefy);


import chart from 'chart.js'
import toastr from 'toastr'

window.axios = require('axios');
$("#btn-menu").click(function(){
    $('.menu-movil').toggleClass('menu-movil-active');

});

Vue.component('resumen-ventas', require('./components/resumen-ventas.vue').default);

const app = new Vue({
	el: '#app'
});
