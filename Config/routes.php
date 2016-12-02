<?php
Router::connect('/', array('controller' => 'pages', 'action' => 'display', 'home'));

Router::connect('/admin', array('controller' => 'administradores', 'action' => 'index', 'admin' => true));
Router::connect('/admin/login', array('controller' => 'administradores', 'action' => 'login', 'admin' => true));

Router::connect('/seccion/*', array('controller' => 'pages', 'action' => 'display'));

CakePlugin::routes();
require CAKE . 'Config' . DS . 'routes.php';
