<?php

/*
	Ax-Noticias 1.0
	Articulos.php
	Autor: Edgardo Putelli (Lalo)
	Mail: aspectox.creativa@gmail.com
	Web: http://aspectox.esy.es/
--------------------------------------------------------*/


# Seguridad
defined('INDEX_DIR') OR exit('Ocrend software says .i.');

//------------------------------------------------



final class Ax_Noticias extends Models implements OCREND {

	public function __construct() {
		parent::__construct();
	}

	public function crear_articulo(){
		Helper::load('strings');
		Helper::load('Mensaje');

		$text = $_POST['texto'];
		$pattern = '#<hr id="system-readmore" />#i';
		$tagPos = preg_match($pattern, $text);

		if ($tagPos == 0){
			$introtexto = $text;
			$fulltexto = '';
		}else{
			list($introtexto, $fulltexto) = preg_split($pattern, $text, 2);
		}

		if ($_POST['alias'] == ""){
			$alias = Strings::alias($_POST['titulo']);
		}else{
			$alias = ($_POST['alias']);
		}

		$obj = new Tags;
		$obj->guardar($_POST['etiquetas']);

		$a=array(
			'titulo' => $_POST['titulo'],
			'alias' => $alias,
			'categoria' => ($_POST['categoria']),
			'etiquetas' => ($_POST['etiquetas']),
			'introtexto' => $introtexto,
			'fulltexto' => $fulltexto,
			'autor' => ($_POST['autor']),
			'fuente' => ($_POST['fuente']),
			'foto' => ($_POST['foto']),
			'epigrafe' => ($_POST['epigrafe_img']),
			'fuente_imagen' => ($_POST['fuente_img']),
			'video' => ($_POST['video']),
			'epigrafe_video' => ($_POST['epigrafe_vid']),
			'fuente_video' => ($_POST['fuente_vid']),
			'audio' => ($_POST['audio']),
			'epigrafe_audio' => ($_POST['epigrafe_audi']),
			'fuente_audio' => ($_POST['fuente_audi']),
			'creado' => ($_SESSION['USUARIO']),
			'fecha_creado' => date('Y-m-d H:i:s'),
			'metadescripcion' => ($_POST['metadescrip']),
			'metapalabraclave' => ($_POST['metapalabra']),
			'fecha_inicio' => ($_POST['fecha_inicio']),
			'fecha_finaliza' => ($_POST['fecha_fin']),
			'destacado' => ($_POST['destacado']),
			'revision' => 0,
			'vistas' => 0,
			'publicar' => $_POST['publica']
		);
		$this->db->insert('articulos', $a);
		Mensaje::msg_exito("Bien!, el artículo fue creado con exito.");
		Func::redir(URL. 'ax-noticias/articulos/');
	}


	public function editar_articulo(){
		Helper::load('Mensaje');

		$text = $_POST['texto'];
		$pattern = '#<hr id="system-readmore" />#i';
		$tagPos = preg_match($pattern, $text);
		if ($tagPos == 0){
			$introtexto = $text;
			$fulltexto = '';
		}else{
			list($introtexto, $fulltexto) = preg_split($pattern, $text, 2);
		}

		if ($_POST['alias'] == ""){
			$alias = Strings::alias($_POST['titulo']);
		}else{
			$alias = ($_POST['alias']);
		}

		if($_POST['publica'] != 0){
			$activar=1;
		}else{
			$activar=0;
		}

		if($_POST['destacado'] != 0 ){
			$destacado=1;
		}else{
			$destacado=0;
		}

		$obj = new Tags;
		$obj->guardar($_POST['etiquetas']);

		$articulo = $this->db->select('*','articulos',"id='$this->id'", 'LIMIT 1');

		$revision = $articulo[0][22];
		$visitas = $articulo[0][23];

		$a=array(
			'titulo' => $_POST['titulo'],
			'alias' => $alias,
			'categoria' => ($_POST['categoria']),
			'etiquetas' => ($_POST['etiquetas']),
			'introtexto' => $introtexto,
			'fulltexto' => $fulltexto,
			'autor' => ($_POST['autor']),
			'fuente' => ($_POST['fuente']),
			'foto' => ($_POST['foto']),
			'epigrafe' => ($_POST['epigrafe_img']),
			'fuente_imagen' => ($_POST['fuente_img']),
			'video' => ($_POST['video']),
			'epigrafe_video' => ($_POST['epigrafe_vid']),
			'fuente_video' => ($_POST['fuente_vid']),
			'audio' => ($_POST['audio']),
			'epigrafe_audio' => ($_POST['epigrafe_audi']),
			'fuente_audio' => ($_POST['fuente_audi']),
			'creado' => ($_SESSION['USUARIO']),
			//'fecha_creado' => date('Y-m-d H:i:s'),
			'metadescripcion' => ($_POST['metadescrip']),
			'metapalabraclave' => ($_POST['metapalabra']),
			'fecha_modificado' => date('Y-m-d H:i:s'),
			'fecha_inicio' => ($_POST['fecha_inicio']),
			'fecha_finaliza' => ($_POST['fecha_fin']),
			'revision' => $revision + 1,
			'vistas' => $visitas,
			'destacado' => $destacado,
			'publicar' => $activar
		);

		$this->db->update('articulos',$a, "id='$this->id'", 'LIMIT 1');
		Mensaje::msg_exito("Bien!, el artículo se modifico con exito.");
		Func::redir(URL. 'ax-noticias/articulos/');
	}

	public function get_articulo(){
		$articulos = $this->db->select('*','articulos',"id='$this->id'", 'LIMIT 1');

		if(false != $articulos){
			return $articulos[0];
		}
		Func::redir(URL. 'ax-noticias/articulos/');
		exit;
	}

	public function get_articulos(){
		return $this->db->select('*','articulos','1=1','ORDER BY id DESC');
	}

	public function get_articulos_masleidos(){
		return $this->db->select('*', 'articulos',"vistas !='0'",'LIMIT 5','ORDER BY vistas DESC');
	}

	public function borrar(){
		Helper::load('Mensaje');
		$this->db->delete('articulos',"id='$this->id'", 'LIMIT 1');
		Mensaje::msg_exito("Bien!, el articulo fue eliminado con exito.");
		Func::redir(URL. 'ax-noticias/articulos/');
	}

	final public function borra_articulo(array $data) : array {
		if(isset($data['articulo_id'])) {
			$reg = $data['articulo_id'];
			foreach ($reg as $value){
				$this->db->delete('articulos',"id='$value'", 'LIMIT 1');
			}
			$success = 1;
			$message = 'Los datos se borraron correctamente';
		} else {
			$success = 0;
			$message = 'No hay nada seleccionado.';
		}

		return array('success' => $success, 'message' => $message);
	}

	final public function desactiva_articulo(array $data) : array {
		if(isset($data['articulo_id'])) {
			$reg = $data['articulo_id'];
			$arreglo = array('publicar'=>'0');
			foreach ($reg as $value){
				$this->db->update('articulos',$arreglo,"id='$value'", 'LIMIT 1');
			}
			$success = 1;
			$message = 'Se han desactivado correctamente';
		} else {
			$success = 0;
			$message = 'No hay nada seleccionado.';
		}

		return array('success' => $success, 'message' => $message);
	}

	final public function activa_articulo(array $data) : array {
		if(isset($data['articulo_id'])) {
			$reg = $data['articulo_id'];
			$arreglo = array('publicar'=>'1');
			foreach ($reg as $value){
				$this->db->update('articulos',$arreglo,"id='$value'", 'LIMIT 1');
			}
			$success = 1;
			$message = 'Se han desactivado correctamente';
		} else {
			$success = 0;
			$message = 'No hay nada seleccionado.';
		}

		return array('success' => $success, 'message' => $message);
	}

	//-------------------------------------------------------------------------
	# Categoria
	public function crear_categoria(){
		Helper::load('strings');
		Helper::load('Mensaje');

		$u=array(
			'titulo' => $_POST['titulo'],
			'alias' => Strings::alias($_POST['titulo']),
			'imagen' => $_POST['imagen'],
			'cat_color' => $_POST['color'],
			'publicar' => $_POST['publica']
		);
		$this->db->insert('categoria', $u);
		Mensaje::msg_exito("Bien!, la categoría fue creada con exito.");
		Func::redir(URL. 'ax-noticias/categorias/');
	}

	public function editar_categoria(){

		Helper::load('Mensaje');

		if(empty($_POST['publica']!= 0)){
			$activar=0;
		}else{
			$activar=1;
		}

		$e=array(
			'titulo' => $_POST['titulo'],
			'alias' => Strings::alias($_POST['titulo']),
			'imagen' => $_POST['imagen'],
			'cat_color' => $_POST['color'],
			'publicar' => $activar
		);

		$this->db->update('categoria',$e, "id='$this->id'", 'LIMIT 1');
		Mensaje::msg_exito("Bien!, la categoría fue modificada con exito.");
		Func::redir(URL. 'ax-noticias/categorias/');
	}

	public function get_categoria(){
		$categoria = $this->db->select('*','categoria',"id='$this->id'", 'LIMIT 1');

		if(false != $categoria){
			return $categoria[0];
		}
		Func::redir(URL. 'ax-noticias/categorias/');
		exit;
	}

	public function get_categorias(){
		return $this->db->select('*', 'categoria');
	}

	public function get_categorias_habilitadas(){
		return $this->db->select('*', 'categoria', "publicar=1");
	}

	final public function borra_categoria(array $data) : array {
		if(isset($data['categoria_id'])) {
			$reg = $data['categoria_id'];
			foreach ($reg as $value){
				$this->db->delete('categoria',"id='$value'", 'LIMIT 1');
			}
			$success = 1;
			$message = 'Los datos se borraron correctamente';
		} else {
			$success = 0;
			$message = 'No hay nada seleccionado.';
		}

		return array('success' => $success, 'message' => $message);
	}

	final public function desactiva_categoria(array $data) : array {
		if(isset($data['categoria_id'])) {
			$reg = $data['categoria_id'];
			$arreglo = array('publicar'=>'0');
			foreach ($reg as $value){
				$this->db->update('categoria',$arreglo,"id='$value'", 'LIMIT 1');
			}
			$success = 1;
			$message = 'Se han desactivado correctamente';
		} else {
			$success = 0;
			$message = 'No hay nada seleccionado.';
		}

		return array('success' => $success, 'message' => $message);
	}

	final public function activa_categoria(array $data) : array {
		if(isset($data['categoria_id'])) {
			$reg = $data['categoria_id'];
			$arreglo = array('publicar'=>'1');
			foreach ($reg as $value){
				$this->db->update('categoria',$arreglo,"id='$value'", 'LIMIT 1');
			}
			$success = 1;
			$message = 'Se han desactivado correctamente';
		} else {
			$success = 0;
			$message = 'No hay nada seleccionado.';
		}

		return array('success' => $success, 'message' => $message);
	}

	//-------------------------------------------------------------------------
	# Autores
	public function crear_autores(){
		Helper::load('strings');
		Helper::load('Mensaje');

		if ($_POST['alias'] == ""){
			$alias = Strings::alias($_POST['nombre']);
		}else{
			$alias = ($_POST['alias']);
		}

		$a=array(
			'nombre' => $_POST['nombre'],
			'alias' => $alias,
			'imagen' => $_POST['imagen'],
			'link' => $_POST['link'],
			'descripcion' => $_POST['descripcion']
		);

		$this->db->insert('autores', $a);
		Mensaje::msg_exito("Bien!, el autor fue creado con exito.");
		Func::redir(URL. 'ax-noticias/autores/');
	}

	public function editar_autores(){

		Helper::load('Mensaje');

		if ($_POST['alias'] == ""){
			$alias = Strings::alias($_POST['nombre']);
		}else{
			$alias = ($_POST['alias']);
		}

		$articulo = $this->db->select('*','autores',"id='$this->id'", 'LIMIT 1');

		$a=array(
			'nombre' => $_POST['nombre'],
			'alias' => $alias,
			'imagen' => $_POST['imagen'],
			'link' => $_POST['link'],
			'descripcion' => $_POST['descripcion']
		);

		$this->db->update('autores',$a, "id='$this->id'", 'LIMIT 1');
		Mensaje::msg_exito("Bien!, el autor se modifico con exito.");

		Func::redir(URL. 'ax-noticias/autores/');
	}

	public function get_autor(){
		$autor = $this->db->select('*','autores',"id='$this->id'", 'LIMIT 1');

		if(false != $autor){
			return $autor[0];
		}
		Func::redir(URL. 'ax-noticias/autores/');
		exit;
	}

	public function get_autores(){
		return $this->db->select('*', 'autores');
	}

	public function borrar_autores(){
		Helper::load('Mensaje');
		$this->db->delete('autores',"id='$this->id'", 'LIMIT 1');
		Mensaje::msg_exito("Bien!, el autor se borro con exito.");
		Func::redir(URL. 'ax-noticias/autores/');
	}

	final public function borra_autores(array $data) : array {
		if(isset($data['autores_id'])) {
			$reg = $data['autores_id'];
			foreach ($reg as $value){
				$this->db->delete('autores',"id='$value'", 'LIMIT 1');
			}
			$success = 1;
			$message = 'Los datos se borraron correctamente';
		} else {
			$success = 0;
			$message = 'No hay nada seleccionado.';
		}

		return array('success' => $success, 'message' => $message);
	}


	//-------------------------------------------------------------------------
	# Fuentes
	public function crear_fuentes(){
		Helper::load('strings');
		Helper::load('Mensaje');

		if ($_POST['alias'] == ""){
			$alias = Strings::alias($_POST['nombre']);
		}else{
			$alias = ($_POST['alias']);
		}

		$a=array(
			'nombre' => $_POST['nombre'],
			'alias' => $alias,
			'imagen' => $_POST['imagen'],
			'link' => $_POST['link'],
			'descripcion' => $_POST['descripcion']
		);

		$this->db->insert('fuentes', $a);
		Mensaje::msg_exito("Bien!, la fuente fue creada con exito.");
		Func::redir(URL. 'ax-noticias/fuentes/');
	}

	public function editar_fuentes(){

		Helper::load('Mensaje');

		if ($_POST['alias'] == ""){
			$alias = Strings::alias($_POST['nombre']);
		}else{
			$alias = ($_POST['alias']);
		}

		$articulo = $this->db->select('*','autores',"id='$this->id'", 'LIMIT 1');

		$a=array(
			'nombre' => $_POST['nombre'],
			'alias' => $alias,
			'imagen' => $_POST['imagen'],
			'link' => $_POST['link'],
			'descripcion' => $_POST['descripcion']
		);

		$this->db->update('fuentes',$a, "id='$this->id'", 'LIMIT 1');
		Mensaje::msg_exito("Bien!, la fuente se a modificado con exito.");
		Func::redir(URL. 'ax-noticias/fuentes/');
	}

	public function get_fuente(){
		$fuentes = $this->db->select('*','fuentes',"id='$this->id'", 'LIMIT 1');

		if(false != $fuentes){
			return $fuentes[0];
		}
		Func::redir(URL. 'ax-noticias/fuentes/');
		exit;
	}

	public function get_fuentes(){
		return $this->db->select('*', 'fuentes');
	}

	public function borrar_fuentes(){
		Helper::load('Mensaje');
		$this->db->delete('fuentes',"id='$this->id'", 'LIMIT 1');
		Mensaje::msg_exito("La fuente se ha borrado.");
		Func::redir(URL. 'ax-noticias/fuentes/');
	}

	final public function borra_fuentes(array $data) : array {
		if(isset($data['fuentes_id'])) {
			$reg = $data['fuentes_id'];
			foreach ($reg as $value){
				$this->db->delete('fuentes',"id='$value'", 'LIMIT 1');
			}
			$success = 1;
			$message = 'Los datos se borraron correctamente';
		} else {
			$success = 0;
			$message = 'No hay nada seleccionado.';
		}

		return array('success' => $success, 'message' => $message);
	}

	//-------------------------------------------------------------------------
	# Tags
	public function crear_tags(){
		Helper::load('strings');
		Helper::load('Mensaje');

		if ($_POST['alias'] == ""){
			$alias = Strings::alias($_POST['titulo']);
		}else{
			$alias = ($_POST['alias']);
		}

		$a=array(
			'tag' => $_POST['titulo'],
			'alias' => $alias
		);

		$this->db->insert('etiquetas', $a);
		Mensaje::msg_exito("Bien!, el tag fue creado con exito.");
		Func::redir(URL. 'ax-noticias/tags/');
	}

	public function editar_tags(){
		Helper::load('strings');
		Helper::load('Mensaje');

		if ($_POST['alias'] == ""){
			$alias = Strings::alias($_POST['titulo']);
		}else{
			$alias = ($_POST['alias']);
		}

		$a=array(
			'tag' => $_POST['titulo'],
			'alias' => $alias
		);


		$this->db->update('etiquetas',$a, "id='$this->id'", 'LIMIT 1');
		Mensaje::msg_exito("Bien!, el tag se modifico con exito.");
		Func::redir(URL. 'ax-noticias/tags/');
	}

	public function guardar_tags($dato){
		Helper::load('strings');

		$entrada = $this->db->select('tag', 'etiquetas');
		$etique = $dato;
		$entrada2 = explode(",",$etique);
		foreach ($entrada2 as $valor) {
			$u = $this->db->select('tag','etiquetas',"tag='$valor'",'LIMIT 1');
			if(false != $u) {

			}else{
				if ($_POST['alias'] == ""){
					$alias = Strings::alias($_POST['titulo']);
				}else{
					$alias = ($_POST['alias']);
				}

				$a = array(
					'tag' => $valor,
					'alias' => $alias
				);
				$this->db->insert('etiquetas', $a );
			}
		}
	}

	public function get_tag(){
		$tag = $this->db->select('*','etiquetas',"id='$this->id'", 'LIMIT 1');

		if(false != $tag){
			return $tag[0];
		}

		Func::redir(URL. 'ax-noticias/tags/');
		exit;
	}

	public function get_tags(){
		return $this->db->select('*', 'etiquetas');
	}

	final public function borra_tags(array $data) : array {
		if(isset($data['tags_id'])) {
			$reg = $data['tags_id'];
			foreach ($reg as $value){
				$this->db->delete('etiquetas',"id='$value'", 'LIMIT 1');
			}
			$success = 1;
			$message = 'Los datos se borraron correctamente';
		} else {
			$success = 0;
			$message = 'No hay nada seleccionado.';
		}

		return array('success' => $success, 'message' => $message);
	}

	//-------------------------------------------------------------------------
	# Tapas
	public function crear_tapas(){
		Helper::load('strings');
		Helper::load('Mensaje');

		$imagen="";

		if(isset($_FILES['files'])){
			$errors= array();
			foreach($_FILES['files']['tmp_name'] as $key => $tmp_name ){
				$file_name = $_FILES['files']['name'][$key];
				$file_size =$_FILES['files']['size'][$key];
				$file_tmp =$_FILES['files']['tmp_name'][$key];
				$file_type=$_FILES['files']['type'][$key];
				if($file_size > 2097152){
					$errors[]='File size must be less than 2 MB';
				}

				$desired_dir = "../imagenes/tapas";

				$imagen .= ("$desired_dir/".$file_name). '|';

				if(empty($errors)==true){
					if(is_dir($desired_dir)==false){
						mkdir("$desired_dir", 0700);		// Create directory if it does not exist
					}
					if(is_dir("$desired_dir/".$file_name)==false){
						move_uploaded_file($file_tmp,"$desired_dir/".$file_name);
					}else{									// rename the file if another one exist
						$new_dir="$desired_dir/".$file_name.time();
						rename($file_tmp,$new_dir) ;
					}
				}else{
					Mensaje::msg_error("Ups!, hay un error" . $errors .".");
				}

			}

			$a=array(
				'nombre' => $_POST['nombre'],
				'imagen' => $imagen
			);
			$this->db->insert('tapas', $a);

			if(empty($error)){
				Mensaje::msg_exito("Bien!, la tapa fue creada con exito.");
			}
		}

		Func::redir(URL. 'ax-noticias/tapas/');
	}

	public function editar_tapas(){

		Helper::load('Mensaje');
		$desired_dir = "../imagenes/tapas/";
		foreach($_POST['archivo'] as $value){
			$imagen .= $desired_dir . $value . '|';
		}

		echo $imagen;
		exit;

		$a=array(
			'nombre' => $_POST['nombre'],
			'imagen' => $imagen
		);

		$this->db->update('tapas',$a, "id='$this->id'", 'LIMIT 1');
		Func::redir(URL. 'ax-noticias/tapas/'. $this->id);
	}

	public function get_tapa(){
		$fuentes = $this->db->select('*','tapas',"id='$this->id'", 'LIMIT 1');

		if(false != $fuentes){
			return $fuentes[0];
		}
		Func::redir(URL. 'ax-noticias/tapas/');
		exit;
	}

	public function get_tapas(){
		return $this->db->select('*', 'tapas');
	}

	public function borrar_tapas(){
		Helper::load('Mensaje');
		$this->db->delete('fuentes',"id='$this->id'", 'LIMIT 1');
		Mensaje::msg_exito("La fuente se ha borrado.");
		Func::redir(URL. 'ax-noticias/tapas/');
	}

	final public function borra_tapas(array $data) : array {
		if(isset($data['tapas_id'])) {
			$reg = $data['tapas_id'];
			foreach ($reg as $value){
				$this->db->delete('tapas',"id='$value'", 'LIMIT 1');
			}
			$success = 1;
			$message = 'Los datos se borraron correctamente';
		} else {
			$success = 0;
			$message = 'No hay nada seleccionado.';
		}

		return array('success' => $success, 'message' => $message);
	}

	public function __destruct() {
		parent::__destruct();
	}

}

?>
