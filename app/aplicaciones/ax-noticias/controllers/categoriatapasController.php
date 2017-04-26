<?php

/*
	Ax-Noticias 1.0
	categoriasController.php
	Autor: Edgardo Putelli (Lalo)
	Mail: aspectox.creativa@gmail.com
	Web: http://aspectox.esy.es/
--------------------------------------------------------*/


# Seguridad
defined('INDEX_DIR') OR exit('Ocrend software says .i.');

//------------------------------------------------

class categoriatapasController extends Controllers {

	public function __construct() {
		parent::__construct(true);

		if( $_SESSION['GRUPO'] <= 3 ){
			Func::redir(URL . './');
		}



		$obj = new Categoria_tapas;


		switch($this->method){
			case 'crear':
				if($_POST){
					$obj->crear();
				}else{
					echo $this->template->render('html/ax-noticias/ctapas/crear');
				}
				break;

			case 'editar':
				if($this->isset_id){
				  if($_POST){
					  $obj->editar();
				  }else{
					  $categoria = $obj->get_categoria();
					  echo $this->template->render('html/ax-noticias/ctapas/editar', array(
						  'cat' => $categoria
					  ));
				  }
				}else{
				  Func::redir(URL . 'tapas/');
				}
				break;

			case 'borrar':
				if($this->isset_id){
					$obj->borrar();
				}else{
					Func::redir(URL . 'tapas/');
				}
				break;

			default:
				$categoria = $obj->get_categoriast();
				echo $this->template->render('html/ax-noticias/ctapas/categoria', array(
					'categoria' => $categoria
				));
				break;
		}
	}

}

?>
