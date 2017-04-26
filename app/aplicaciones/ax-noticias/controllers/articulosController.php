<?php

/*
	Ax-Noticias 1.0
	articulosController.php
	Autor: Edgardo Putelli (Lalo)
	Mail: aspectox.creativa@gmail.com
	Web: http://aspectox.esy.es/
--------------------------------------------------------*/


# Seguridad
defined('INDEX_DIR') OR exit('Ocrend software says .i.');

//------------------------------------------------

class articulosController extends Controllers {

	public function __construct() {
		parent::__construct(true);

		if( $_SESSION['GRUPO'] <= 3 ){
			Func::redir(URL . './');
		}

		$obj = new Ax_Noticias;

		switch($this->method){
			case 'crear':
				if($_POST){
					$obj->crear_articulo();
				}else{
					$categoria = $obj1->get_categorias_habilitadas();
					$tags = $obj2->get_tags();
					$autor = $obj3->get_autores();
					$fuente = $obj4->get_fuentes();
					echo $this->template->render('html/ax-noticias/articulos/crear', array(
						  'categoria' => $categoria,
						  'tag' => $tags,
						  'aut' => $autor,
						  'fuente' => $fuente
					  ));
				}
				break;

			case 'editar':
				if($this->isset_id){
				  if($_POST){
					  $obj->editar_articulo();
				  }else{
					  $articulos = $obj->get_articulo();
					  $categoria = $obj1->get_categorias_habilitadas();
					  $tags = $obj2->get_tags();
					  $autor = $obj3->get_autores();
					  $fuente = $obj4->get_fuentes();
					  echo $this->template->render('html/ax-noticias/articulos/editar', array(
						  'ar' => array('art' => $articulos),
						  'ca' => array('cat' => $categoria),
						  'tag' => array('ta' => $tags),
						  'aut' => array('au' => $autor),
						  'fuen' => array('fue' => $fuente)
					  ));
				  }
				}else{
				  Func::redir(URL . 'ax-noticias/articulos/');
				}
				break;

			case 'borrar':
				if($this->isset_id){
					$obj->borrar_articulo();
				}else{
					Func::redir(URL . 'ax-noticias/articulos/');
				}
				break;

			default:
				$articulos = $obj->get_articulos();
				echo $this->template->render('html/ax-noticias/articulos/articulos', array(
						  'articulos' => $articulos
				));
				break;
		}
	}

}

?>
