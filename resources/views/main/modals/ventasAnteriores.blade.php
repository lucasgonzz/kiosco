<div class="modal fade" id="ventasAnteriores" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Ventas anteriores de @{{ article.name }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="alert alert-primary" role="alert">
          <strong>@{{ ventasAnteriores.length }}</strong> vendidos
        </div>
        <ul class="list-group">
          <li v-for="ventaAnterior in ventasAnteriores" v-bind:class="ventaAnterior.created_at==sale.created_at ? 'azul' : ''" class="list-group-item">
            <p>
              @{{ ventaAnterior.dia }}
              <strong><i class="fas fa-table"></i> @{{ ventaAnterior.creado }}</strong>
              <i class="far fa-clock m-l-10"></i> @{{ ventaAnterior.hora }} hs
            </p>
            <p v-if="ventaAnterior.created_diff == 0">
              Hoy
            </p>
            <p v-else>
              Hace @{{ ventaAnterior.created_diff }}
            </p>
          </li>
        </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>