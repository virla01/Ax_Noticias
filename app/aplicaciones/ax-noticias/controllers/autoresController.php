<?php


/*
	Ax-Noticias 1.0
	autoresController.php
	Autor: Edgardo Putelli (Lalo)
	Mail: aspectox.creativa@gmail.com
	Web: http://aspectox.esy.es/
--------------------------------------------------------*/


# Seguridad
defined('INDEX_DIR') OR exit('Ocrend software says .i.');

//------------------------------------------------

class autoresController extends Controllers {

	public function __construct() {
		parent::__construct(true);
		if( $_SESSION['GRUPO'] <= 3 ){
			Func::redir(URL . './');
		}

		$obj = new Ax_Noticias;

		switch($this->method){
			case 'crear':
				if($_POST){
					$obj->crear_autores();
				}else{
					echo $this->template->render('html/ax-noticias/autores/crear');
				}
				break;

			case 'editar':
				if($this->isset_id){
					if($_POST){
						$obj->editar_autores();
					}else{
						$autor = $obj->get_autor();
						echo $this->template->render('html/ax-noticias/autores/editar', array(
							'autor' => $autor
						));
					}
				}else{
					Func::redir(URL . 'ax-noticias/autores/');
				}
				break;

			case 'borrar':
				if($this->isset_id){
					$obj->borrar_autores();
				}else{
					Func::redir(URL . 'ax-noticias/autores/');
				}
				break;

			default:
				$autor = $obj->get_autores();
				echo $this->template->render('html/ax-noticias/autores/autores', array(
					'autores' => $autor
				));
				break;
		}
	}

}

?>
