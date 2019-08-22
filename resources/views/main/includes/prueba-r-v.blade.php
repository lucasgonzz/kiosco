<div class="card">
	<div class="card-header p-0 b-b-none">
		<ul class="nav nav-tabs f-w" id="myTab" role="tablist">
			<li class="nav-item">
				<a class="nav-link c-g active nav-lg" id="hoy-tab" data-toggle="tab" href="#hoy" role="tab" aria-controls="hoy" aria-selected="true">Hoy</a>
			</li>
			<li class="nav-item">
				<a class="nav-link c-g nav-lg" id="desde-hasta-tab" data-toggle="tab" href="#desde-hasta" role="tab" aria-controls="profile" aria-selected="false">Desde-Hasta una fecha</a>
			</li>
		</ul>
	</div>
	<div class="card-body"> 
		<div class="tab-content" id="myTabContent">
			<div class="tab-pane fade show active" id="hoy" role="tabpanel" aria-labelledby="hoy-tab">
				<form>
					<div class="form-row">
						<div class="col-12 col-lg-2">
							<div class="form-check">
							</div>
							<b-radio type="radio" v-model="mostrar" id="ventas-hoy" native-value="ventas-hoy">Todo el dia</b-radio>
							<b-radio type="radio" v-model="mostrar" id="" native-value="ventas-hoy">Mañana</b-radio>
							<b-radio type="radio" v-model="mostrar" id="" native-value="ventas-hoy">Tarde</b-radio>
						</div>
					</div>
				</form>
			</div>
			<div class="tab-pane fade" id="desde-hasta" role="tabpanel" aria-labelledby="desde-hasta-tab">
				<form>
						desde hasta					
				</form>
			</div>
		</div>
	</div>
</div>