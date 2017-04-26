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

class categoriasController extends Controllers {

	public function __construct() {
		parent::__construct(true);

		if( $_SESSION['GRUPO'] <= 3 ){
			Func::redir(URL . './');
		}

		$obj = new Ax_Noticias;

		switch($this->method){
			case 'crear':
				if($_POST){
					$obj->crear_categoria();
				}else{
					echo $this->template->render('html/ax-noticias/categorias/crear');
				}
				break;

			case 'editar':
				if($this->isset_id){
				  if($_POST){
					  $obj->editar_categoria();
				  }else{
					  $categoria = $obj->get_categoria();
					  echo $this->template->render('html/ax-noticias/categorias/editar', array(
						  'cat' => $categoria
					  ));
				  }
				}else{
				  Func::redir(URL . 'categorias/');
				}
				break;

			case 'borrar':
				if($this->isset_id){
					$obj->borrar_categoria();
				}else{
					Func::redir(URL . 'categorias/');
				}
				break;

			default:
				$categoria = $obj->get_categorias();
				echo $this->template->render('html/ax-noticias/categorias/categorias', array(
					'categoria' => $categoria
				));
				break;
		}
	}

}

?>
