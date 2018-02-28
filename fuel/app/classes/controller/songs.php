<?php

use Firebase\JWT\JWT;

class Controller_Songs extends Controller_Rest{

	private $key = 'my_secret_key';
	protected $format = 'json';

	function post_create(){
		
        try {

            $jwt = apache_request_headers()['Authorization'];

            if (!isset($_POST['title']) || $_POST['title'] == "" || $_POST['url_youtube'] == "" || !isset($_POST['url_youtube']) || $_POST['artist'] == "" || !isset($_POST['artist'])) 
            {
                return $this->createResponse(400, 'Parámetros incorrectos');
            }

            if($this->validateToken($jwt)){

                $token = JWT::decode($jwt, $this->key, array('HS256'));
                $id = $token->data->id;
     
                $usuario = Model_Users::find($id);

                if($usuario->id_rol != 1){
                    return $this->createResponse(400, 'El usuario debe ser administrador');
                }

            	$title = $_POST['title'];
            	$url_youtube = $_POST['url_youtube'];
                $artist = $_POST['artist'];

	            if($this->songExists($url_youtube)){
                    return $this->createResponse(400, 'Canción ya existente');
                }

                $props = array('title' => $title, 'url_youtube' => $url_youtube, 'artist' => $artist, 'reproductions' => 0);

                $new = new Model_Songs($props);
                $new->save();

                return $this->createResponse(200, 'Canción creada', ['song' => $new]);


	        }else{
	        	return $this->createResponse(400, 'No estás autorizado para realizar esta acción');
	        }

        }
        catch (Exception $e) 
        {
            return $this->createResponse(500, $e->getMessage());

        }      
	}

    function post_song(){
        try{
            $jwt = apache_request_headers()['Authorization'];

            if($this->validateToken($jwt)){

                if(!isset($_POST['id']) || $_POST['id'] == ""){
                    return $this->createResponse(400, 'Parámetros incorrectos, falta parámetro id');
                }

                $id = $_POST['id'];

                $token = JWT::decode($jwt, $this->key, array('HS256'));
                $idUserLoged = $token->data->id;
           
                $song = Model_Songs::find($id);

                if($song != null){
                    $song->reproductions += 1;
                    $song->save();

                    $listLastListened = Model_Lists::find('first', array(
                        'where' => array(
                            array('editable', 0),
                            array('id_user', $idUserLoged)
                        )));

                    $contain = Model_Contain::find('first', array(
                        'where' => array(
                            array('id_list', $listLastListened->id),
                            array('id_song', $id)
                    )));

                    if($contain != null){
                        $contain->delete();
                    }

                    $containNew = new Model_Contain(array('id_song' => $id, 'id_list' => $listLastListened->id));
                    $containNew->save();

                    return $this->createResponse(200, 'Canción devuelta', ['song' => $song]);
                }else{
                    return $this->createResponse(400, 'La canción no existe');
                }
              
            }else{

                return $this->createResponse(400, 'No tienes permiso para realizar esta acción');

            }
        }catch (Exception $e) {
            return $this->createResponse(500, $e->getLine());

        }
    }

    function get_song(){
        try{
            $jwt = apache_request_headers()['Authorization'];

            if($this->validateToken($jwt)){

                if(!isset($_GET['id']) || $_GET['id'] == ""){
                    return $this->createResponse(400, 'Parámetros incorrectos, falta parámetro id');
                }

                $id = $_GET['id'];
           
                $song = Model_Songs::find($id);

                if($song != null){
                    return $this->createResponse(200, 'Canción devuelta', ['song' => $song]);
                }else{
                    return $this->createResponse(400, 'La canción no existe');
                }
              
            }else{

                return $this->createResponse(400, 'No tienes permiso para realizar esta acción');

            }
        }catch (Exception $e) {
            return $this->createResponse(500, $e->getMessage());

        }
    }

	function post_delete(){
        try{
            $jwt = apache_request_headers()['Authorization'];

            if($this->validateToken($jwt)){

                $token = JWT::decode($jwt, $this->key, array('HS256'));
                $id = $token->data->id;
     
                $usuario = Model_Users::find($id);

                if ($usuario->id_rol != 1){
                    return $this->createResponse(400, 'No tienes permiso para realizar esta acción');
                }

                if(!isset($_POST['id_song']) || $_POST['id_song'] == ""){
                    return $this->createResponse(400, 'Parámetros incorrectos');
                }

                $id_song = $_POST['id_song'];
           
                $song = Model_Songs::find($id_song);

                if($song != null){
                    $song->delete();

                    return $this->createResponse(200, 'Canción borrada correctamente', ['song' => $song]);
                }else{
                    return $this->createResponse(400, 'La canción no existe');
                }
              
            }else{

                return $this->createResponse(400, 'No tienes permiso para realizar esta acción');

            }
        }catch (Exception $e) {
            return $this->createResponse(500, $e->getMessage());

        }
    	
    }

	function get_songs(){

        try{
            $jwt = apache_request_headers()['Authorization'];

            if($this->validateToken($jwt)){
              
                $songs = Model_Songs::find('all');

                if($songs != null){
                    $this->createResponse(200, 'Canciones devueltas', ['songs' => $songs]);
                }else{
                    $this->createResponse(200, 'No hay canciones');
                }

            }else{

              $this->createResponse(400, 'No tienes permiso para realizar esta acción');

            }
        }catch (Exception $e) {
            $this->createResponse(500, $e->getMessage());
        }

    }

    function post_edit(){

        try{
            $jwt = apache_request_headers()['Authorization'];

            if($this->validateToken($jwt)){

                $token = JWT::decode($jwt, $this->key, array('HS256'));
                $id = $token->data->id;
     
                $usuario = Model_Users::find($id);

                if ($usuario->id_rol != 1){
                    return $this->createResponse(400, 'No tienes permiso para realizar esta acción');
                }

                if(!isset($_POST['id_song']) || $_POST['id_song'] == ""){
                    return $this->createResponse(400, 'Es necesario el parámetro id');
                }

                $id_song = $_POST['id_song'];
                $song = Model_Songs::find($id_song);

                if($song == null){
                    return $this->createResponse(400, 'La canción no existe');
                }

                if (empty($_POST['title']) && empty($_POST['url_youtube']) && empty($_POST['artist']) ){
                    return $this->createResponse(400, 'Parámetros incorrectos, es necesario al menos uno (title o url o artist)');
                }

                if (!empty($_POST['title'])){
                    $song->title = $_POST['title'];  
                }

                if (!empty($_POST['url_youtube'])){

                    if(songExists($_POST['url_youtube'])){
                        return $this->createResponse(400, 'Ya existe una canción con esa URL');
                    }

                    $song->url_youtube = $_POST['url_youtube'];
                }

                if (!empty($_POST['artist'])){
                    $song->artist = $_POST['artist'];
                }

                $song->save();

                return $this->createResponse(200, 'Canción editada',['song' => $song]);
                
            }else{

              return $this->createResponse(400, 'No tienes permiso para realizar esta acción');

            }

        }catch (Exception $e) {
            $this->createResponse(500, $e->getMessage());

        } 
        
    }

    function get_lastSongListened(){
        try{
            $jwt = apache_request_headers()['Authorization'];

            if($this->validateToken($jwt)){

                if(!isset($_GET['id_user']) || $_GET['id_user'] == ""){
                    return $this->createResponse(400, 'Parámetros incorrectos, falta parámetro id_user');
                }

                $id = $_GET['id_user'];

                $user = Model_Users::find($id);

                $privacity = Model_Privacity::find($user->id_privacity);

                if($privacity->lists == 0){
                    return $this->createResponse(200, 'El usuario no permite que se muestre esta información');
                }

                $listLastListened = Model_Lists::find('first', array(
                        'where' => array(
                            array('editable', 0),
                            array('id_user', $id)
                        )));

                $contain = Model_Contain::find('last', array(
                        'where' => array(
                            array('id_list', $listLastListened->id)
                        )));

                if($contain == null){
                    return $this->createResponse(200, 'El usuario no ha escuchado ninguna canción');
                }

                $lastSong = Model_Songs::find($contain->id_song);

                return $this->createResponse(200, 'Última canción escuchada devuelta', ['song' => $lastSong]);

            }
        }catch (Exception $e) {
            $this->createResponse(500, $e->getMessage());

        } 
    }

	function createResponse($code, $message, $data = []){

        $json = $this->response(array(
            'code' => $code,
            'message' => $message,
            'data' => $data
            ));

        return $json;

    }



    function songExists($url_youtube){

        $songs = Model_Songs::find('all', array(
                  'where' => array(
                      array('url_youtube', $url_youtube)
                )));

        if($songs != null){
            return true;
        }else{
            return false;
        }

   }

    function validateToken($jwt){
        $token = JWT::decode($jwt, $this->key, array('HS256'));

        $username = $token->data->username;
        $password = $token->data->password;

        $userDB = Model_Users::find('all', array(
        'where' => array(
              array('username', $username),
              array('password', $password)
            )
        ));

        if($userDB != null){
            return true;
        }else{
            return false;
        }
    }

}