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

	/**
	 * Función encargada de crear el contenido HTML del newsletter según su base HTML
	 * @param 	(Int) 	$id 	Identificador del nnewsletter 	
	 */
	public function admin_generarHtml($id = null, $save = false) {

			$htmlEmail = $this->Email->find('first', array(
				'conditions' => array('id' => $id), 
				'fields' => array('html','nombre', 'sitio_url'),
				'contain'	=> array('Categoria')	
				)
			);

			if (empty($htmlEmail)) {
				$this->Session->setFlash('Registro inválido.', null, array(), 'danger');
				$this->redirect(array('action' => 'index'));
			}

			// Nombre del Newsletter
			$htmlNombre = $htmlEmail['Email']['nombre'];

			// Url del sitio que corresponde el newsletter
			$SitioUrl = $htmlEmail['Email']['sitio_url'];

			$categoriasId = Hash::extract($htmlEmail['Categoria'], '{n}.id');

			$categorias = ClassRegistry::init('Categoria')->find('all', array(
					'conditions' => array(
						'Categoria.id'	=> $categoriasId,
						'Categoria.activo' => 1
					),
					'order'	=> array(
						'Categoria.orden DESC'
					)
				)
			);

			// Se genera HTML para el newsletter (ver modelo Email)
			$htmlEmail = $this->Email->armarHtmlEmail($htmlEmail);

			$bloque = array();
			$seccion = array();

			// Orden de productos por defecto
			$ordenProductos = array(
				'Toolmania.id_product DESC'
			);


			// Dos columnas por defecto
			$dosColumnas = true;

			App::uses('CakeNumber', 'Utility');
			App::uses('CakeText', 'Utility');

			foreach ($categorias as $indice => $categoria) {

				/**
				* Condiciones para odenar productos
				*/
				if (isset($categoria['Categoria']['orden_productos']) && ! empty($categoria['Categoria']['orden_productos'])) {
					
					switch ($categoria['Categoria']['orden_productos']) {
						case 'nombre_asc':
							$ordenProductos = array(
								'pl.name ASC'
							);
							break;
						case 'nombre_desc':
							$ordenProductos = array(
								'pl.name DESC'
							);
							break;
						case 'precio_asc':
							$ordenProductos = array(
								'Toolmania.price ASC'
							);
							break;
						case 'precio_desc':
							$ordenProductos = array(
								'Toolmania.price DESC'
							);
							break;
						case 'referencia_asc':
							$ordenProductos = array(
								'Toolmania.reference ASC'
							);
							break;
						case 'referencia_desc':
							$ordenProductos = array(
								'Toolmania.reference DESC'
							);
							break;
					}
				}

				/**
				* Obtenemos los productos elacionados a la categoría
				*/
				$relacionados = ClassRegistry::init('Categoria')->CategoriasToolmania->find('all', array(
					'fields' => array('CategoriasToolmania.id_product'),
					'conditions' => array('CategoriasToolmania.categoria_id' => $categoria['Categoria']['id'])
					)
				);

				// Arreglo para alojar los IDs de los productos relacionados
				$arrayRelacionadosId = array();

				// Agregamos al arreglo $arrayRelacionadosId los IDs de los productos
				foreach ($relacionados as $relacionado) {
					$arrayRelacionadosId[] = $relacionado['CategoriasToolmania']['id_product'];
				}

				// Buscamos los productos que cumplan con el criterio
				$productos	= ClassRegistry::init('Categoria')->Toolmania->find('all', array(
					'fields' => array(
						'concat(\'http://www.toolmania.cl/img/p/\',mid(im.id_image,1,1),\'/\', if (length(im.id_image)>1,concat(mid(im.id_image,2,1),\'/\'),\'\'),if (length(im.id_image)>2,concat(mid(im.id_image,3,1),\'/\'),\'\'),if (length(im.id_image)>3,concat(mid(im.id_image,4,1),\'/\'),\'\'),if (length(im.id_image)>4,concat(mid(im.id_image,5,1),\'/\'),\'\'), im.id_image, \'-large_default.jpg\' ) AS url_image',
						'Toolmania.id_product', 
						'pl.name', 
						'Toolmania.price', 
						'pl.link_rewrite', 
						'Toolmania.reference', 
						'Toolmania.show_price'
					),
					'joins' => array(
						array(
				            'table' => 'tm_product_lang',
				            'alias' => 'pl',
				            'type'  => 'LEFT',
				            'conditions' => array(
				                'Toolmania.id_product=pl.id_product'
				            )

			        	),
			        	array(
				            'table' => 'tm_image',
				            'alias' => 'im',
				            'type'  => 'LEFT',
				            'conditions' => array(
				                'Toolmania.id_product = im.id_product'
				            )
			        	)
					),
					'contain' => array(
						'TaxRulesGroup' => array(
							'TaxRule' => array(
								'Tax'
							)
						),
						'SpecificPrice' => array(
							'conditions' => array(
								'OR' => array(
									'OR' => array(
										array('SpecificPrice.from' => '000-00-00 00:00:00'),
										array('SpecificPrice.to' => '000-00-00 00:00:00')
									),
									'AND' => array(
										'SpecificPrice.from <= "' . date('Y-m-d H:i:s') . '"',
										'SpecificPrice.to >= "' . date('Y-m-d H:i:s') . '"'
									)
								)
							)
						),
						'SpecificPricePriority'
					),
					'conditions' => array(
						'Toolmania.id_product' => $arrayRelacionadosId,
						'Toolmania.active' => 1,
						'Toolmania.available_for_order' => 1,
						'Toolmania.id_shop_default' => 1,
						'pl.id_lang' => 1 
					),
					'order' => $ordenProductos
				));

				// Agregamos los productos al arreglo final
				$categoria['Producto'] = $productos;


				// Se abre el bloque sección
				$seccion[$indice] = $htmlEmail['seccion_categoria'] . $htmlEmail['categoria'];

				// Agregamos el nombre de la categoría al HTML
				$seccion[$indice] = str_replace('[**categoria_nombre**]',$categoria['Categoria']['nombre'], $seccion[$indice]);

				// 2 o 3 columnas de prodctos
				$dosColumnas = ( $categoria['Categoria']['tres_columnas'] ) ? false : true;

				/**
				* Agregamos los productos al HTML
				*/
				foreach ($categoria['Producto'] as $llave => $producto) {
					
					// Retornar valor del producto con iva;
					$producto['Toolmania']['valor_iva'] = $this->precio($producto['Toolmania']['price'], $producto['TaxRulesGroup']['TaxRule'][0]['Tax']['rate']);
					

					// Criterio del precio específico del producto
					foreach ($producto['SpecificPricePriority'] as $criterio) {
						$precioEspecificoPrioridad = explode(';', $criterio['priority']);
					}

					// Retornar último precio espeficico según criterio del producto
					foreach ($producto['SpecificPrice'] as $precio) {
						if ( $precio['reduction'] == 0 ) {
							$producto['Toolmania']['valor_final'] = $producto['Toolmania']['valor_iva'];
						}else{
							$producto['Toolmania']['valor_final'] = $this->precio($producto['Toolmania']['valor_iva'], ($precio['reduction'] * 100 * -1) );
							$producto['Toolmania']['descuento'] = ($precio['reduction'] * 100 * -1 );
						}
					}

					/**
					* Información del producto
					*/
					$urlProducto 			= $producto[0]['url_image'];
					$porcentaje_descuento 	= ( !empty($producto['Toolmania']['descuento']) ) ? $producto['Toolmania']['descuento'] . '%' : '<font size="2">Oferta</font>' ;
					$nombre_producto		= CakeText::truncate($producto['pl']['name'], 40, array('exact' => false));
					$modelo_producto		= $producto['Toolmania']['reference'];
					$valor_producto			= ( $precio['reduction'] != 0 ) ? CakeNumber::currency($producto['Toolmania']['valor_iva'] , 'CLP') : '' ;
					$oferta_producto		= CakeNumber::currency($producto['Toolmania']['valor_final'] , 'CLP');
					$url_producto			= sprintf('%s%s-%s.html', $SitioUrl, $producto['pl']['link_rewrite'], $producto['Toolmania']['id_product']);


					/**
					* Agregamos la información al HTML del email
					*/
					$seccion[$indice] .= ( $dosColumnas ) ? $htmlEmail['bloque_2'] : $htmlEmail['bloque_3'] ;
					$seccion[$indice] = str_replace('[**url_imagen_producto**]',$urlProducto, $seccion[$indice]);
					$seccion[$indice] = str_replace('[**porcentaje_producto**]', $porcentaje_descuento, $seccion[$indice]);
					$seccion[$indice] = str_replace('[**nombre_producto**]', $nombre_producto , $seccion[$indice]);
					$seccion[$indice] = str_replace('[**modelo_producto**]', $modelo_producto , $seccion[$indice]);
					$seccion[$indice] = str_replace('[**antes_producto**]', $valor_producto , $seccion[$indice]);
					$seccion[$indice] = str_replace('[*valor_producto*]', $oferta_producto , $seccion[$indice]);
					$seccion[$indice] = str_replace('[**url_producto**]', $url_producto , $seccion[$indice]);
				}


				// Se cierra el bloque sección
				$seccion[$indice] .= $htmlEmail['seccion_bloque'];

			}			

		
			// Unimos los contenidos
			$seccion = implode($seccion, '');

			$htmlFinal = '';

			// Limpiamos espacios en blanco y asignamos HTML de cabecera
			$htmlFinal .= trim($htmlEmail['cabecera']);

			// Agregamos HTML de secciones y productos
			$htmlFinal .= $seccion;

			// Agregamos HTML del footer
			$htmlFinal .= $htmlEmail['footer'];

			
			if ( $save ) {
				// Guardar ultimo html generado
				$data = array(
					'Email' => array(
						'id' => $id,
						'ultimo_html' => $htmlFinal,
						'semaforo' => true
					)
				);	

				$this->Email->save($data);
			}

			

			$this->set(compact('htmlFinal', 'htmlNombre'));

	}

}

