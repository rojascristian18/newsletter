<?php

App::uses('AppController', 'Controller');

class CategoriasController extends AppController

{

	public function admin_index()

	{

		$conditions = array();

		// Condicones para super administrador
		if ( $this->Auth->user('admin') == 0 ) {
			$conditions = array('Categoria.activo' => 1);
		}

		$this->paginate		= array(

			'recursive'			=> 0,
			'limit'	=> 1000,
			'conditions' => $conditions

		);

		$categorias	= $this->paginate();

		$this->set(compact('categorias'));

	}



	public function admin_add()

	{

		if ( $this->request->is('post') )

		{

			$this->Categoria->create();

			if ( $this->Categoria->saveAll($this->request->data) )

			{

				$this->Session->setFlash('Registro agregado correctamente.', null, array(), 'success');

				$this->redirect(array('action' => 'index'));

			}

			else

			{

				$this->Session->setFlash('Error al guardar el registro. Por favor intenta nuevamente.', null, array(), 'danger');

			}

		}

		$emails	= $this->Categoria->Email->find('list');

		$productos	= $this->Categoria->Producto->find('list', array('conditions' => array('Producto.activo' => 1)));

		$this->set(compact('emails', 'productos'));

	}



	public function admin_edit($id = null)

	{

		if ( ! $this->Categoria->exists($id) )

		{

			$this->Session->setFlash('Registro inválido.', null, array(), 'danger');

			$this->redirect(array('action' => 'index'));

		}



		if ( $this->request->is('post') || $this->request->is('put') )

		{

			/**

			* Se eliminan las relaciones categorias-productos para volver a agregarlas 

			*/

			$this->Categoria->CategoriasProducto->deleteAll(

				array(

					'CategoriasProducto.categoria_id' => $id,

				)

           	);



			if ( $this->Categoria->saveAll($this->request->data) )

			{

				$this->Session->setFlash('Registro editado correctamente', null, array(), 'success');

				$this->redirect(array('action' => 'index'));

			}

			else

			{

				$this->Session->setFlash('Error al guardar el registro. Por favor intenta nuevamente.', null, array(), 'danger');

			}

		}

		else

		{

			$this->request->data	= $this->Categoria->find('first', array(

				'conditions'	=> array('Categoria.id' => $id),

				'contain'		=> array('Producto')

			));

		}



		$emails	= $this->Categoria->Email->find('list');

		$productos	= $this->Categoria->Producto->find('list', array('conditions' => array('Producto.activo' => 1)));





		$this->set(compact('emails', 'productos'));

	}



	public function admin_delete($id = null)

	{

		$this->Categoria->id = $id;

		if ( ! $this->Categoria->exists() )

		{

			$this->Session->setFlash('Registro inválido.', null, array(), 'danger');

			$this->redirect(array('action' => 'index'));

		}



		$this->request->onlyAllow('post', 'delete');

		if ( $this->Categoria->delete() )

		{

			$this->Session->setFlash('Registro eliminado correctamente.', null, array(), 'success');

			$this->redirect(array('action' => 'index'));

		}

		$this->Session->setFlash('Error al eliminar el registro. Por favor intenta nuevamente.', null, array(), 'danger');

		$this->redirect(array('action' => 'index'));

	}



	public function admin_exportar()

	{

		$datos			= $this->Categoria->find('all', array(

			'recursive'				=> -1

		));

		$campos			= array_keys($this->Categoria->_schema);

		$modelo			= $this->Categoria->alias;



		$this->set(compact('datos', 'campos', 'modelo'));

	}





	/**

	* Función que desactiva una categoria

	* @param $id 	Integer 	Identificador de la categoria

	*/

	public function admin_desactivar($id = null)

	{

		$this->Categoria->id = $id;

		if ( ! $this->Categoria->exists() )

		{

			$this->Session->setFlash('La categoria no existe.', null, array(), 'danger');

			$this->redirect(array('action' => 'index'));

		}

		

		if ( $this->Categoria->saveField('activo', 0) )

		{

			$this->Session->setFlash('Categoria desactivada correctamente.', null, array(), 'success');

			$this->redirect(array('action' => 'index'));

		}

		$this->Session->setFlash('Error al desactivar la categoria. Por favor intenta nuevamente.', null, array(), 'danger');

		$this->redirect(array('action' => 'index'));

	}





	/**

	* Función que activa un producto

	* @param $id 	Integer 	Identificador del producto

	*/

	public function admin_activar($id = null)

	{

		$this->Categoria->id = $id;

		if ( ! $this->Categoria->exists() )

		{

			$this->Session->setFlash('La categoria no existe.', null, array(), 'danger');

			$this->redirect(array('action' => 'index'));

		}



		

		if ( $this->Categoria->saveField('activo', 1) )

		{

			$this->Session->setFlash('Producto activado correctamente.', null, array(), 'success');

			$this->redirect(array('action' => 'index'));

		}

		$this->Session->setFlash('Error al desactivar la categoria. Por favor intenta nuevamente.', null, array(), 'danger');

		$this->redirect(array('action' => 'index'));

	}

}

