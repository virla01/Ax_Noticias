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

class tapasController extends Controllers {

	public function __construct() {
		parent::__construct(true);
		if( $_SESSION['GRUPO'] <= 3 ){
			Func::redir(URL . './');
		}

		$obj = new Ax_Noticias;

		switch($this->method){
			case 'crear':
				if($_POST){
					$obj->crear_tapas();
				}else{
					echo $this->template->render('html/ax-noticias/tapas/crear');
				}
				break;

			case 'editar':
				if($this->isset_id){
					if($_POST){
						$obj->editar_tapas();
					}else{
						$tapas = $obj->get_tapa();
						echo $this->template->render('html/ax-noticias/tapas/editar', array(
							'tapas' => $tapas
						));
					}
				}else{
					Func::redir(URL . 'ax-noticias/tapas/');
				}
				break;

			case 'borrar':
				if($this->isset_id){
					$obj->borrar_tapas();
				}else{
					Func::redir(URL . 'ax-noticias/tapas/');
				}
				break;

			default:
				$tapas = $obj->get_tapas();
				echo $this->template->render('html/ax-noticias/tapas/tapas', array(
					'tapas' => $tapas
				));
				break;
		}
	}

}

?>
