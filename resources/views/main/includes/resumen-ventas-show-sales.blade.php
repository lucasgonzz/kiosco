<div class="row" v-if="mostrar == 0">
			<div class="col">
				<div class="accordion" id="acordeon">
					<div class="card" v-for="(sale, index) in sales">
						<div class="card-header" v-bind:id="'heading' + index">
							<h2 class="mb-0">
								<button class="btn" type="button" data-toggle="collapse" v-bind:data-target="'#collapse' + index" v-bind:aria-expanded="index == 0 ? 'true' : 'false'" v-bind:aria-controls="'collapse' + index">
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
								<div class="row">
									<div class="col-12 col-lg-6">
										<!-- <ul class="list-group" v-for="article in sale.articles">
											<li class="list-group-item">@{{ article.name }} | $@{{ article.price }}</li>
										</ul> -->
										<table class="table">
											<thead class="thead-dark">
												<tr>
													<th scope="col">Articulo</th>
													<th scope="col">Precio</th>
													<th scope="col">Ventas anteriores</th>
												</tr>
											</thead>
											<tbody>
												<tr v-for="article in sale.articles">
													<td>@{{ article.name }}</td>
													<td>$@{{ article.price }}</td>
													<td v-if="article.sales.length>1"><a href="#" class="btn btn-outline-primary" @click.prevent="showVentasAnteriores(article)">Ventas anteriores</a></td>
												</tr>
											</tbody>
										</table> 
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
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