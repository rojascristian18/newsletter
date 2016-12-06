<?php
App::uses('Controller', 'Controller');
//App::uses('FB', 'Facebook.Lib');
class AppController extends Controller
{
	public $helpers		= array(
		'Session', 'Html', 'Form', 'PhpExcel'
		//, 'Facebook.Facebook'
	);
	public $components	= array(
		'Session',
		'Auth'		=> array(
			'loginAction'		=> array('controller' => 'administradores', 'action' => 'login', 'admin' => true),
			'loginRedirect'		=> '/emails',
			'logoutRedirect'	=> '/',
			'authError'			=> 'No tienes permisos para entrar a esta sección.',
			'authenticate'		=> array(
				'Form'				=> array(
					'userModel'			=> 'Usuario',
					'fields'			=> array(
						'username'			=> 'email',
						'password'			=> 'clave'
					)
				)
			)
		),
		'Google'		=> array(
			'applicationName'		=> 'Newsletter Nodriza',
			'developerKey'			=> 'cristian.rojas@nodriza.cl',
			'clientId'				=> '1376469050-ckai861jm571qcguj2ohgepgb605uu2l.apps.googleusercontent.com',
			'clientSecret'			=> 'Kfmh_BoEMaD6nbMHSfA8CEyW',
			//'redirectUri'			=> Router::url(array('controller' => 'administradores', 'action' => 'google', 'admin' => false), true)),
			'approvalPrompt'		=> 'auto',
			'accessType'			=> null,//'offline',
			'scopes'				=> array('profile', 'email')
		),
		'DebugKit.Toolbar',
		//'Facebook.Connect'	=> array('model' => 'Usuario'),
		//'Facebook'
	);

	public function beforeFilter()
	{
		/**
		 * Layout administracion y permisos publicos
		 */
		if ( ! empty($this->request->params['admin']) )
		{
			$this->layoutPath				= 'backend';
			AuthComponent::$sessionKey		= 'Auth.Administrador';
			$this->Auth->authenticate['Form']['userModel']		= 'Administrador';
		}
		else
		{
			AuthComponent::$sessionKey	= 'Auth.Usuario';
			$this->Auth->allow();
		}

		/**
		 * OAuth Google
		 */
		$this->Google->cliente->setRedirectUri(Router::url(array('controller' => 'administradores', 'action' => 'login'), true));
		$this->Google->oauth();

		if ( ! empty($this->request->query['code']) && $this->Session->read('Google.code') != $this->request->query['code'] )
		{
			$this->Google->oauth->authenticate($this->request->query['code']);
			$this->Session->write('Google', array(
				'code'		=> $this->request->query['code'],
				'token'		=> $this->Google->oauth->getAccessToken()
			));
		}

		if ( $this->Session->check('Google.token') )
		{
			$this->Google->cliente->setAccessToken($this->Session->read('Google.token'));
		}

		/**
		 * Logout FB
		 */
		/*
		if ( ! isset($this->request->params['admin']) && ! $this->Connect->user() && $this->Auth->user() )
			$this->Auth->logout();
		*/

		/**
		 * Detector cliente local
		 */
		$this->request->addDetector('localip', array(
			'env'			=> 'REMOTE_ADDR',
			'options'		=> array('::1', '127.0.0.1'))
		);

		/**
		 * Detector entrada via iframe FB
		 */
		$this->request->addDetector('iframefb', array(
			'env'			=> 'HTTP_REFERER',
			'pattern'		=> '/facebook\.com/i'
		));

		/**
		 * Cookies IE
		 */
		header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
	}

	/**
	 * Guarda el usuario Facebook
	 */
	public function beforeFacebookSave()
	{
		if ( ! isset($this->request->params['admin']) )
		{
			$this->Connect->authUser['Usuario']		= array_merge(array(
				'nombre_completo'	=> $this->Connect->user('name'),
				'nombre'			=> $this->Connect->user('first_name'),
				'apellido'			=> $this->Connect->user('last_name'),
				'usuario'			=> $this->Connect->user('username'),
				'clave'				=> $this->Connect->authUser['Usuario']['password'],
				'email'				=> $this->Connect->user('email'),
				'sexo'				=> $this->Connect->user('gender'),
				'verificado' 		=> $this->Connect->user('verified'),
				'edad'				=> $this->Session->read('edad')
			), $this->Connect->authUser['Usuario']);
		}

		return true;
	}


	public function beforeRender(){
		$avatar = $this->obtenerAvatar();

		$permiso = $this->hasPermission();

		$this->set(compact('avatar', 'permiso'));
	}


	/**
	* Función que permite obtener el avatar de un administrador
	* @return  		array()
	*/
	private function obtenerAvatar(){
		return ClassRegistry::init('Administrador')->find('first', array(
			'fields' => array(
				'google_imagen'), 
			'conditions' => array(
				'id' => $this->Auth->user('id') 
			)
		));
	}

	/**
	* Functión que determina si el usuario tien permisos de administrador
	* @return bool
	*/ 
	public function hasPermission()
	{
		$admin = ClassRegistry::init('Administrador')->find('first', array(
				'conditions' => array(
					'Administrador.id' 		=> $this->Auth->user('id'),
					'Administrador.admin' 	=> 1
				)
			)
		);

		if ( empty($admin) ) {
			return false;
		}

		return true;
	}

	/**
	 * Función que lista las categorías disponibles
	 * @return array()
	 */
	public function getCategoriesList() {
		$categorias = ClassRegistry::init('Categoria')->find('list', array('conditions' => array('Categoria.activo' => 1)));
		return $categorias;
	}

	/**
	* Calular IVA
	* @param 	$precio 	num 	Valor del producto
	* @param 	$iva 		bool 	Valor del IVA
	* @return 	Integer 	Valor calculado
	*/
	public function precio($precio = null, $iva = null) {
		if ( !empty($precio) && !empty($iva)) {
			// Se quitan los 00
			$iva = intval($iva);

			//Calculamos valor con IVA
			$precio = ($precio + round( ( ($precio*$iva) / 100) ) );

			return round($precio);
		}
	}
}
