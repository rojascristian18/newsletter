<div class="page-sidebar">
	<ul class="x-navigation x-navigation-custom">
		<li class="xn-logo">
			<?= $this->Html->link(
				'<span class="fa fa-dashboard"></span> <span class="x-navigation-control">Backend</span>',
				'/admin',
				array('escape' => false)
			); ?>
			<a href="#" class="x-navigation-control"></a>
		</li>
		<li class="xn-profile active">
            <div class="profile">
            	<div class="profile-image">
            		<? if ( empty($avatar['Administrador']['google_imagen']) ) : ?>
            			<?=$this->Html->image('logo_user.jpg', array('alt' => 'Perfil','class' => 'mCS_img_loaded')); ?>
            			
            		<? else : ?>
            			<? $avatarSz =  str_replace('sz=50', 'sz=200', $avatar['Administrador']['google_imagen']);?>
						<img class="mCS_img_loaded" src="<?=$avatarSz;?>" alt="Perfil">
            		<? endif; ?>
                </div>
                <div class="profile-data">
                    <div class="profile-data-name"><?=$this->Session->read('Auth.Administrador.nombre');?></div>
                </div>
            </div>                                                                        
        </li>
		<li class="xn-title">Newsletter </li>
		<li class="<?= ($this->Html->menuActivo(array('controller' => 'emails', 'action' => 'index')) ? 'active' : ''); ?>">
		<?= $this->Html->link(
				'<span class="fa fa-envelope"></span> <span class="xn-text">Newsletter</span>',
				'/emails/index',
				array('escape' => false)
			); ?>
		</li>
		<? if ( $permiso ) : ?>
		<li class="<?= ($this->Html->menuActivo(array('controller' => 'plantillas', 'action' => 'index')) ? 'active' : ''); ?>">
		<?= $this->Html->link(
				'<span class="fa fa-file"></span> <span class="xn-text">Plantillas</span>',
				'/plantillas/index',
				array('escape' => false)
			); ?>
		</li>
		<? endif; ?>
		<li class="xn-title">Productos</li>
		<li class="<?= ($this->Html->menuActivo(array('controller' => 'categorias', 'action' => 'index')) ? 'active' : ''); ?>">
		<?= $this->Html->link(
				'<span class="fa fa-list-ol"></span> <span class="xn-text">Categorías</span>',
				'/categorias/index',
				array('escape' => false)
			); ?>
		</li>
		<? if ( $permiso ) : ?>
		<li class="<?= ($this->Html->menuActivo(array('controller' => 'productos', 'action' => 'index')) ? 'active' : ''); ?>">
		<?= $this->Html->link(
				'<span class="fa fa-shopping-bag"></span> <span class="xn-text">Productos</span>',
				'/productos/index',
				array('escape' => false)
			); ?>
		</li>
		<? endif; ?>
		<li class="<?= ($this->Html->menuActivo(array('controller' => 'toolmanias', 'action' => 'index')) ? 'active' : ''); ?>">
		<?= $this->Html->link(
				'<span class="fa fa-shopping-bag"></span> <span class="xn-text">Productos Toolmania</span>',
				'/toolmanias/index',
				array('escape' => false)
			); ?>
		</li>

		<? if ( $permiso ) : ?>

			<li class="xn-title">Administración </li>
			<li class="<?= ($this->Html->menuActivo(array('controller' => 'administradores', 'action' => 'index')) ? 'active' : ''); ?>">
			<?= $this->Html->link(
					'<span class="fa fa-users"></span> <span class="xn-text">Administradores</span>',
					'/',
					array('escape' => false)
				); ?>
			</li>

		<?	$controladores		=  array_map(function($controlador)
			{
				return str_replace('Controller', '', $controlador);
			}, App::objects('controller'));
			?>
			<li class="xn-openable">
				<a href="#"><span class="fa fa-cog"></span> <span class="xn-text">Controladores</span></a>
				<ul>
					<? foreach ( $controladores as $controlador ) : ?>
					<? if ( $controlador === 'App' ) continue; ?>
					<li class="<?= ($this->Html->menuActivo(array('controller' => strtolower($controlador))) ? 'active' : ''); ?>">
						<?= $this->Html->link(
							sprintf('<span class="fa fa-list"></span> <span class="xn-text">%s</span>', ucfirst($controlador)),
							array('controller' => strtolower($controlador), 'action' => 'index'),
							array('escape' => false)
						); ?>
					</li>
					<? endforeach; ?>
				</ul>
			</li>
		<? endif; ?>
	</ul>
</div>
