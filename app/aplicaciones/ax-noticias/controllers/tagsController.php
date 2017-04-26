<?php

/*
	Ax-Noticias 1.0
	tagsController.php
	Autor: Edgardo Putelli (Lalo)
	Mail: aspectox.creativa@gmail.com
	Web: http://aspectox.esy.es/
--------------------------------------------------------*/


# Seguridad
defined('INDEX_DIR') OR exit('Ocrend software says .i.');

//------------------------------------------------

class tagsController extends Controllers {

	public function __construct() {
		parent::__construct(true);

		if( $_SESSION['GRUPO'] <= 3 ){
			Func::redir(URL . './');
		}

		$obj = new Ax_Noticias;

		switch($this->method){

			case 'crear':
				if($_POST){
					$obj->crear_tags();
				}else{
					echo $this->template->render('html/ax-noticias/tags/crear');
				}
				break;
			case 'editar':
				if($this->isset_id){
					if($_POST){
						$obj->editar_tags();
					}else{
						$tags = $obj->get_tag();
						echo $this->template->render('html/ax-noticias/tags/editar', array(
						  'tag' => array('ta' => $tags)
						));
					}
				}else{
					Func::redir(URL . 'tags/');
				}
				break;
			default:
				$tags = $obj->get_tags();
				echo $this->template->render('html/ax-noticias/tags/tags', array(
					'tags' => $tags
				));
				break;

		}

	}
}

?>
