<div class="row justify-content-center" v-if="mostrar == 0">
	<div class="col col-lg-6">
		<ul class="list-group">
			<li class="list-group-item p-b-10" v-for="sale in sales">
				<div class="media media-absolute">
					<i class="far fa-clock fa-3x"></i>
					<div class="media-body m-l-5">
						<h3 class="mt-0 h5">@{{ sale.hora }}</h3>
						<p class="h6">$@{{ sale.total }}</p>
					</div>
				</div>
				<table class="table m-lg-l-120">
					<thead class="thead-dark">
						<tr>
							<th scope="col">Nombre</th>
							<th scope="col">Precio</th>
							<th scope="col">Quedan</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="article in sale.articles">
							<th class="f-w-0" style="width: ">@{{ article.name }}</th>
							<th class="f-w-0">$@{{ article.price }}</th>
							<th class="f-w-0">@{{ article.stock }}</th>
						</tr>
					</tbody>
				</table>
			</li>	
		</ul>
	</div>
</div>

<div class="row" v-else>
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
				<template v-for="sale in sales">
					<tr v-for="article in sale.articles">
						<td>@{{ sale.hora }}</td>
						<td>@{{ article.name }}</td>
						<td>$@{{ article.price }}</td>
						<td v-if="article.sales.length>1"><a href="#" class="btn btn-outline-primary" @click.prevent="showVentasAnteriores(article)">Ventas anteriores</a></td>
					</tr>
				</template>
			</tbody>
		</table>
	</div>
</div>