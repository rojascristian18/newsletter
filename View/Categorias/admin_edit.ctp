<div class="page-title">
	<h2><span class="fa fa-list-ol"></span> Categorias</h2>
</div>

<div class="page-content-wrap">
	<div class="row">
		<div class="col-xs-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Editar Categoria</h3>
				</div>
				<div class="panel-body">
					<div class="table-responsive">
						<?= $this->Form->create('Categoria', array('class' => 'form-horizontal', 'type' => 'file', 'inputDefaults' => array('label' => false, 'div' => false, 'class' => 'form-control'))); ?>
							<table class="table">
								<?= $this->Form->input('id'); ?>
								<tr>
									<th><?= $this->Form->label('nombre', 'Nombre'); ?></th>
									<td><?= $this->Form->input('nombre'); ?></td>
								</tr>
								<tr>
									<th><?= $this->Form->label('descripcion', 'Descripcion <small>(Opcional)</small>'); ?></th>
									<td><?= $this->Form->input('descripcion'); ?></td>
								</tr>
								<tr>
									<th><?= $this->Form->label('imagen', 'Imagen <small>(Opcional)</small>'); ?></th>
									<td><?= $this->Form->input('imagen', array('type' => 'file', 'class' => '')); ?></td>
								</tr>
								<tr>
									<th><?= $this->Form->label('color_fondo', 'Color fondo <small>(Opcional)</small>'); ?></th>
									<td><?= $this->Form->input('color_fondo'); ?></td>
								</tr>
								<tr>
									<th><?= $this->Form->label('color_titulo', 'Color titulo <small>(Opcional)</small>'); ?></th>
									<td><?= $this->Form->input('color_titulo'); ?></td>
								</tr>
								<tr>
									<th><?= $this->Form->label('color_parrafo', 'Color parrafo <small>(Opcional)</small>'); ?></th>
									<td><?= $this->Form->input('color_parrafo'); ?></td>
								</tr>
								<tr>
									<th><?= $this->Form->label('tres_columnas', '3 columnas'); ?></th>
									<td><?= $this->Form->input('tres_columnas', array('class' => 'icheckbox')); ?></td>
								</tr>
								<tr>
									<th><?= $this->Form->label('activo', 'Activo'); ?></th>
									<td><?= $this->Form->input('activo', array('class' => 'icheckbox')); ?></td>
								</tr>
								<tr>
									<th><?= $this->Form->label('Producto', 'Productos relacionados'); ?></th>
									<td><ul id="sortable" class="list-group border-bottom">
								<?	foreach ($productos as $indice => $producto) : ?>
										<li class="list-group-item" data-related="<?=$indice;?>"  id="relacionado-<?=$indice;?>"><?=$producto;?></li>	
								<?	endforeach; ?>
									</ul>
									</td>
								</tr>
								<!--<tr style="position: relative;">
									<th><label>Orden productos</label></th>
									<td>
									<?= $this->Form->input('meta_orden', array('id' => 'orden') ); ?></td>
								</tr>-->
								<tr>
									<th><label>Orden categoría</label></th>
									<td><?= $this->Form->input('orden'); ?></td>
								</tr>
							</table>

							<div class="pull-right">
								<input type="submit" class="btn btn-primary esperar-carga" autocomplete="off" data-loading-text="Espera un momento..." value="Guardar cambios">
								<?= $this->Html->link('Cancelar', array('action' => 'index'), array('class' => 'btn btn-danger')); ?>
							</div>
						<?= $this->Form->end(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	  /*$( function() {
	 var id_related = [];
    $( "#sortable" ).sortable({
    	 update: function(event, ui) {
	        $('.label-sort').each(function(i) { 
	           id_related.push( $(this).attr('data-related') ); // updates the attribute
	           $('#orden').val(id_related);
	           id_related = [];
	        });
	    }
    });
    $( "#sortable" ).disableSelection();
  } );*/
</script>