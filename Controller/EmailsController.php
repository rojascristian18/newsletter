<?php

App::uses('AppController', 'Controller');

class EmailsController extends AppController

{

	public function admin_index()

	{

		$conditions = array();

		// Condicones para super administrador
		if ( $this->Auth->user('admin') == 0 ) {
			$conditions = array('Email.activo' => 1);
		}

		$this->paginate		= array(

			'recursive'			=> 0,
			'limit'	=> 1000,
			'conditions' => $conditions

		);

		$emails	= $this->paginate();

		$this->set(compact('emails'));

	}



	public function admin_add()

	{

		if ( $this->request->is('post') )

		{	

			$this->Email->create();

			if ( $this->Email->save($this->request->data) )

			{ 	

				$this->Session->setFlash('Registro agregado correctamente.', null, array(), 'success');

				$this->redirect(array('action' => 'index'));

			}

			else

			{

				$this->Session->setFlash('Error al guardar el registro. Por favor intenta nuevamente.', null, array(), 'danger');

			}

		}

		$plantillas	= $this->Email->Plantilla->find('list', array('conditions' => array('activo' => 1)));

		$categorias	= $this->Email->Categoria->find('list', array('conditions' => array('activo' => 1)));

		$this->set(compact('plantillas', 'categorias'));

	}



	public function admin_edit($id = null)

	{

		if ( ! $this->Email->exists($id) )

		{

			$this->Session->setFlash('Registro inválido.', null, array(), 'danger');

			$this->redirect(array('action' => 'index'));

		}



		if ( $this->request->is('post') || $this->request->is('put') )

		{	//prx($this->request->data);



			/**

			* Se eliminan las relaciones categorias-emails para volver a agregarlas 

			*/

			$this->Email->CategoriasEmail->deleteAll(

				array(

					'CategoriasEmail.email_id' => $id,

				)

           	);



			if ( $this->Email->save($this->request->data) )

			{

				$this->Session->setFlash('Registro editado correctamente', null, array(), 'success');

				$this->redirect(array('action' => 'generarHtml', $id));

			}

			else

			{

				$this->Session->setFlash('Error al guardar el registro. Por favor intenta nuevamente.', null, array(), 'danger');

			}

		}

		else

		{

			$this->request->data	= $this->Email->find('first', array(

				'conditions'	=> array('Email.id' => $id),

				'contain'		=> array('Categoria')

			));

		}

		

		$plantillas	= $this->Email->Plantilla->find('list', array('conditions' => array('activo' => 1)));

		$categorias	= $this->Email->Categoria->find('list', array('conditions' => array('activo' => 1)));

		$this->set(compact('plantillas', 'categorias'));

	}



	public function admin_delete($id = null)

	{

		$this->Email->id = $id;

		if ( ! $this->Email->exists() )

		{

			$this->Session->setFlash('Registro inválido.', null, array(), 'danger');

			$this->redirect(array('action' => 'index'));

		}



		$this->request->onlyAllow('post', 'delete');

		if ( $this->Email->delete() )

		{

			$this->Session->setFlash('Registro eliminado correctamente.', null, array(), 'success');

			$this->redirect(array('action' => 'index'));

		}

		$this->Session->setFlash('Error al eliminar el registro. Por favor intenta nuevamente.', null, array(), 'danger');

		$this->redirect(array('action' => 'index'));

	}



	public function admin_exportar()

	{

		$datos			= $this->Email->find('all', array(

			'recursive'				=> -1

		));

		$campos			= array_keys($this->Email->_schema);

		$modelo			= $this->Email->alias;



		$this->set(compact('datos', 'campos', 'modelo'));

	}



	/**

	* Función que desactiva un email

	* @param $id 	Integer 	Identificador del email

	*/

	public function admin_desactivar($id = null)

	{

		$this->Email->id = $id;

		if ( ! $this->Email->exists() )

		{

			$this->Session->setFlash('Newslletter no existe.', null, array(), 'danger');

			$this->redirect(array('action' => 'index'));

		}

		

		if ( $this->Email->saveField('activo', 0) )

		{

			$this->Session->setFlash('Newslletter desactivado correctamente.', null, array(), 'success');

			$this->redirect(array('action' => 'index'));

		}

		$this->Session->setFlash('Error al desactivar el Newslletter. Por favor intenta nuevamente.', null, array(), 'danger');

		$this->redirect(array('action' => 'index'));

	}





	/**

	* Función que activa un email

	* @param $id 	Integer 	Identificador del email

	*/

	public function admin_activar($id = null)

	{

		$this->Email->id = $id;

		if ( ! $this->Email->exists() )

		{

			$this->Session->setFlash('Newslletter no existe.', null, array(), 'danger');

			$this->redirect(array('action' => 'index'));

		}



		

		if ( $this->Email->saveField('activo', 1) )

		{

			$this->Session->setFlash('Newslletter activado correctamente.', null, array(), 'success');

			$this->redirect(array('action' => 'index'));

		}

		$this->Session->setFlash('Error al eliminar el Newslletter. Por favor intenta nuevamente.', null, array(), 'danger');

		$this->redirect(array('action' => 'index'));

	}





	public function admin_view($id =  null) {



		if ( ! $this->Email->exists($id) )

		{

			$this->Session->setFlash('Registro inválido.', null, array(), 'danger');

			$this->redirect(array('action' => 'index'));

		}



		$this->request->data	= $this->Email->find('first', array(

			'conditions'	=> array('Email.id' => $id),

			'contain'		=> array('Categoria')

		));



	}



	public function admin_generarHtml($id = null) {



			$htmlEmail = $this->Email->find('first', array(

				'conditions' => array('id' => $id), 

				'fields' => array('html','nombre'),

				'contain'	=> array('Categoria')	

				)

			);



			if (empty($htmlEmail)) {

				$this->Session->setFlash('Registro inválido.', null, array(), 'danger');

				$this->redirect(array('action' => 'index'));

			}





			$htmlNombre = $htmlEmail['Email']['nombre'];



			$categoriasId = Hash::extract($htmlEmail['Categoria'], '{n}.id');





			$categorias = ClassRegistry::init('Categoria')->find('all', array(

					'conditions' => array(

						'Categoria.id'	=> $categoriasId,

						'Categoria.activo' => 1

					),

					'contain'	=> array('Producto')

				)

			);



			$htmlEmail = $this->Email->armarHtmlEmail($htmlEmail);



			$bloque = array();

			$seccion = array();



			// Dos columnas por defecto

			$dosColumnas = true;



			App::uses('CakeNumber', 'Utility');

			App::uses('CakeText', 'Utility');



			foreach ($categorias as $indice => $categoria) {



				$seccion[$indice] = $htmlEmail['seccion_categoria'] . $htmlEmail['categoria'];



				$seccion[$indice] = str_replace('[**categoria_nombre**]',$categoria['Categoria']['nombre'], $seccion[$indice]);





				$dosColumnas = ( $categoria['Categoria']['tres_columnas'] ) ? false : true;



				foreach ($categoria['Producto'] as $llave => $producto) {



					$urlProducto 			= sprintf('%s/img/%s/%d/%s', Router::url('/', true), 'Producto', $producto['id'], $producto['imagen']);

					$porcentaje_descuento 	= ( !empty($producto['porcentaje_oferta']) ) ? $producto['porcentaje_oferta'] : 0 ;

					$nombre_producto		= CakeText::truncate($producto['nombre'], 40, array('exact' => false));

					$modelo_producto		= $producto['modelo'];

					$valor_producto			= CakeNumber::currency($producto['valor'], 'CLP');

					$oferta_producto		= CakeNumber::currency($producto['valor'] - ( ($producto['valor'] / 100) * $porcentaje_descuento ), 'CLP');

					$url_producto			= $producto['url'];



					$seccion[$indice] .= ( $dosColumnas ) ? $htmlEmail['bloque_2'] : $htmlEmail['bloque_3'] ;

					$seccion[$indice] = str_replace('[**url_imagen_producto**]',$urlProducto, $seccion[$indice]);

					$seccion[$indice] = str_replace('[**porcentaje_producto**]', $porcentaje_descuento . '%' , $seccion[$indice]);

					$seccion[$indice] = str_replace('[**nombre_producto**]', $nombre_producto , $seccion[$indice]);

					$seccion[$indice] = str_replace('[**modelo_producto**]', $modelo_producto , $seccion[$indice]);

					$seccion[$indice] = str_replace('[**antes_producto**]', $valor_producto , $seccion[$indice]);

					$seccion[$indice] = str_replace('[*valor_producto*]', $oferta_producto , $seccion[$indice]);

					$seccion[$indice] = str_replace('[**url_producto**]', $url_producto , $seccion[$indice]);



				}



				$seccion[$indice] .= $htmlEmail['seccion_bloque'];



			}			

		

			$seccion = implode($seccion, '');

			

			$htmlFinal = '';

			$htmlFinal .= trim($htmlEmail['cabecera']);

			

			$htmlFinal .= $seccion;



			$htmlFinal .= $htmlEmail['footer'];



			$this->set(compact('htmlFinal', 'htmlNombre'));



	}

}

