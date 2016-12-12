<div class="page-title">
	<h2><span class="fa fa-envelope"></span> Newsletter</h2>
</div>

<div class="page-content-wrap">
	<div class="row">
		<div class="col-xs-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Listado de newsletter</h3>
					<div class="btn-group pull-right">
						<?= $this->Html->link('<i class="fa fa-plus"></i> Nuevo Newsletter', array('action' => 'add'), array('class' => 'btn btn-success', 'escape' => false)); ?>
						<?= $this->Html->link('<i class="fa fa-file-excel-o"></i> Exportar a Excel', array('action' => 'exportar'), array('class' => 'btn btn-primary', 'escape' => false)); ?>
					</div>
				</div>
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table datatable">
							<thead>
								<tr class="sort">
									<th><?= $this->Paginator->sort('nombre', null, array('title' => 'Haz click para ordenar por este criterio')); ?></th>
									<th><?= $this->Paginator->sort('titulo', null, array('title' => 'Haz click para ordenar por este criterio')); ?></th>
									<th><?= $this->Paginator->sort('plantilla_id', null, array('title' => 'Haz click para ordenar por este criterio')); ?></th>
									<th><?= $this->Paginator->sort('created', 'Creado', array('title' => 'Haz click para ordenar por este criterio')); ?></th>
									<th><?= $this->Paginator->sort('activo', null, array('title' => 'Haz click para ordenar por este criterio')); ?></th>
									<th>Acciones</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ( $emails as $email ) : ?>
								<tr>
									<td><?= h($email['Email']['nombre']); ?>&nbsp;</td>
									<td><?= h($email['Email']['titulo']); ?>&nbsp;</td>
									<td><?= h($email['Plantilla']['nombre']); ?>&nbsp;</td>
									<td><?= h($email['Email']['created']); ?>&nbsp;</td>
									<td><?= ($email['Email']['activo'] ? '<i class="fa fa-check"></i>' : '<i class="fa fa-remove"></i>'); ?>&nbsp;</td>
									<td>
										<?= $this->Html->link('<i class="fa fa-edit"></i> Editar', array('action' => 'edit', $email['Email']['id']), array('class' => 'btn btn-xs btn-info', 'rel' => 'tooltip', 'title' => 'Editar este registro', 'escape' => false)); ?>
										<?= $this->Form->postLink('<i class="fa fa-eye"></i> Generar', array('action' => 'generarHtml', $email['Email']['id']), array('class' => 'btn btn-xs btn-primary', 'rel' => 'tooltip', 'title' => 'Ver', 'escape' => false)); ?>
										<a href="#" class="mb-control btn btn-success btn-xs" data-box="#mb-signout<?=$email['Email']['id'];?>"><i class="fa fa-cogs"></i> Generar y guardar</a>
										<div class="message-box message-box-warning animated fadeIn" data-sound="alert" id="mb-signout<?=$email['Email']['id'];?>">
											<div class="mb-container">
												<div class="mb-middle">
													<div class="mb-title"><span class="fa fa-cogs"></span>¿Generar y <strong>guardar</strong>?</div>
													<div class="mb-content">
														<p>¿Seguro que quieres generar y guardar?</p>
														<p>Se guardará el Html generado sobreescribiendo el anterior.</p>
														<p>Para cancelar presiona No</p>
													</div>
													<div class="mb-footer">
														<div class="pull-right">
															<?= $this->Form->postLink('Aceptar', array('action' => 'generarHtml', $email['Email']['id'], true), array('class' => 'btn btn-lg btn-success', 'rel' => 'tooltip', 'title' => 'Ver', 'escape' => false)); ?>
															<button class="btn btn-default btn-lg mb-control-close">No</button>
														</div>
													</div>
												</div>
											</div>
										</div>
									<? if ($permiso) : ?>
										<? if ($email['Email']['activo'] == 1) { ?>
											<?= $this->Form->postLink('<i class="fa fa fa-eye-slash"></i> Desactivar', array('action' => 'desactivar', $email['Email']['id']), array('class' => 'btn btn-warning btn-xs', 'rel' => 'tooltip', 'title' => 'Desactivar este registro', 'escape' => false)); ?>
										<? }else{ ?>
											<?= $this->Form->postLink('<i class="fa fa-eye"></i> Activar', array('action' => 'activar', $email['Email']['id']), array('class' => 'btn btn-primary btn-xs', 'rel' => 'tooltip', 'title' => 'Activar este registro', 'escape' => false)); ?>
										<?	} ?>
										<?= $this->Form->postLink('<i class="fa fa-trash"></i> Eliminar', array('action' => 'delete', $email['Email']['id']), array('class' => 'btn btn-danger btn-xs', 'rel' => 'tooltip', 'title' => 'Eliminar este registro', 'escape' => false)); ?>
									<? endif; ?>
									</td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="pull-right">
	<ul class="pagination">
		<?= $this->Paginator->prev('« Anterior', array('tag' => 'li'), null, array('tag' => 'li', 'disabledTag' => 'a', 'class' => 'first disabled hidden')); ?>
		<?= $this->Paginator->numbers(array('tag' => 'li', 'currentTag' => 'a', 'modulus' => 2, 'currentClass' => 'active', 'separator' => '')); ?>
		<?= $this->Paginator->next('Siguiente »', array('tag' => 'li'), null, array('tag' => 'li', 'disabledTag' => 'a', 'class' => 'last disabled hidden')); ?>
	</ul>
</div>
