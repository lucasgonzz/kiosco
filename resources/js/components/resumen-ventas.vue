<template>
    <div class="row">
        <div class="col">
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Hora</th>
                        <th scope="col">Articulo</th>
                        <th scope="col">Precio</th>
                        <th scope="col">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- <template id="tem" v-for="sale in sales">
                        <tr v-for="article in sale.articles">
                            <td>@{{ sale.hora }}</td>
                            <td>@{{ article.name }}</td>
                            <td>$@{{ article.price }}</td>
                            <td v-if="article.sales.length>1"><a href="#" class="btn btn-outline-primary" @click.prevent="showVentasAnteriores(article)">Ventas anteriores</a></td>
                        </tr>
                    </template> -->
                    <tr>
                        <td>Hola</td>
                        <td>Hola</td>
                        <td>Hola</td>
                        <td>Hola</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                sales: [],
                sale: {},
                article: {},
                mostrar: 'ventas-hoy',
                total: 0,
                ventasAnteriores: [],
                mostrar: 0,
                ventas_cont: 0,
            }
        },
        created: function(){
            this.getSales();
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
                    console.log(response.data);
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
    }
</script>
