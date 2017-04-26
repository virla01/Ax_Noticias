<?php

/*
	Ax-Noticias 1.0
	fuentesController.php
	Autor: Edgardo Putelli (Lalo)
	Mail: aspectox.creativa@gmail.com
	Web: http://aspectox.esy.es/
--------------------------------------------------------*/


# Seguridad
defined('INDEX_DIR') OR exit('Ocrend software says .i.');

//------------------------------------------------

class fuentesController extends Controllers {

	public function __construct() {
		parent::__construct(true);
		if( $_SESSION['GRUPO'] <= 3 ){
			Func::redir(URL . './');
		}

		$obj = new Ax_Noticias;

		switch($this->method){
			case 'crear':
				if($_POST){
					$obj->crear_fuentes();
				}else{
					echo $this->template->render('html/ax-noticias/fuentes/crear');
				}
				break;

			case 'editar':
				if($this->isset_id){
					if($_POST){
						$obj->editar_fuentes();
					}else{
						$fuente = $obj->get_fuente();
						echo $this->template->render('html/ax-noticias/fuentes/editar', array(
							'fuente' => $fuente
						));
					}
				}else{
					Func::redir(URL . 'fuentes/');
				}
				break;

			case 'borrar':
				if($this->isset_id){
					$obj->borrar_fuentes();
				}else{
					Func::redir(URL . 'fuentes/');
				}
				break;

			default:
				$fuente = $obj->get_fuentes();
				echo $this->template->render('html/ax-noticias/fuentes/fuentes', array(
					'fuente' => $fuente
				));
				break;
		}
	}

}

?>
