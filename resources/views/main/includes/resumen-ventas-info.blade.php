<div class="row m-b-10 justify-content-between" v-show="sales.length">
	<div class="col-12 col-lg-4 align-self-center">
		<div class="tab-content" id="pills-tabContent">
			<div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
				<ul class="list-group list-group-horizontal">
					<li class="list-group-item">@{{ sales.length }} ventas</li>
					<li class="list-group-item">@{{ ventas_cont }} articulos vendidos</li>
					<li class="list-group-item">Resumen: $@{{ total }}</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="col-12 col-lg-3">
		<p class="h5">Ver</p>
		<div class="form-check form-check-inline">
			<b-radio type="radio" v-model="mostrar" name="solo-ventas" native-value="0" id="solo-ventas">Solo las ventas</b-radio>
		</div>
		<div class="form-check form-check-inline">
			<b-radio type="radio" v-model="mostrar" name="ventas-con-articulos" native-value="1" id="ventas-con-articulos">Las ventas y sus articulos</b-radio>
		</div>
	</div>
</div>