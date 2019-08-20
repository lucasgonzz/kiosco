<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Editar <strong>@{{ articulo.name }}</strong> | <i class="fas fa-barcode"></i> @{{ articulo.codigo_barras }}</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="cost">Agregado</label>
					<input type="text" name="cost" v-model="articulo.creado" class="form-control" disabled>
				</div>
				<div class="form-group">
					<label for="cost">Actualizado</label>
					<input type="text" name="cost" v-model="articulo.actualizado" class="form-control" disabled>
				</div>
				<div class="form-group">
					<label for="cost">Costo</label>
					<input type="text" name="cost" v-model="articulo.cost" id="costo" class="form-control focus-red">
				</div>
				<div class="form-group">
					<label for="price">Precio</label>
					<input type="text" name="price" v-model="articulo.price" class="form-control focus-red">
				</div>
				<div class="form-group">
					<label for="name">Nombre</label>
					<input type="text" name="name" v-model="articulo.name" class="form-control focus-red">
				</div>
				<div class="form-group">
					<label for="mayorista">Mayorista</label>
					<select name="mayorista" v-model="articulo.mayorista" class="form-control focus-red">
						<option value="articulo.mayorista" selected="true">@{{ articulo.mayorista }}</option>
						<option v-for="mayorista in mayoristas" v-show="mayorista.name!=articulo.mayorista">@{{ mayorista.name }}</option>
					</select>
				</div>
				<div class="form-group">
					<label for="stock">Cantidad</label>
					<input type="number" v-model="articulo.stock" name="stock" class="form-control focus-red">
				</div>
				<div class="form-check">
					<input class="form-check-input" type="checkbox" v-model="articulo.act_fecha" value="" id="defaultCheck1" checked>
					<label class="form-check-label" for="defaultCheck1">
						Actualizar fecha
					</label>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary focus-red" data-dismiss="modal">Cancelar</button>
				<button type="button" class="btn btn-primary focus-red" v-on:click="updateArticulo(articulo)">Actualizar</button>
			</div>
		</div>
	</div>
</div>
