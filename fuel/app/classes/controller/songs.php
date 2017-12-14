<?php

use Firebase\JWT\JWT;

class Controller_Songs extends Controller_Rest{

	private $key = 'my_secret_key';
	protected $format = 'json';

	function post_create(){
		$jwt = apache_request_headers()['Authorization'];

        try {
            if (!isset($_POST['title']) || $_POST['url_youtube'] == "") 
            {

                $this->createResponse(400, 'Parámetros incorrectos');

            }

            if($this->validateToken($jwt)){

	            	$title = $_POST['title'];
	            	$url_youtube = $_POST['url_youtube'];

	            if(!$this->songExists($title, $url_youtube)){

	                $props = array('titulo' => $title, 'url_youtube' => $url_youtube);

	                $new = new Model_Canciones($props);
	                $new->save();

	                $this->createResponse(200, 'Canción creada', ['song' => $new]);

	            }else{
	                $this->createResponse(400, 'Canción ya existente');
	            }

	        }else{
	        	$this->createResponse(400, 'El token no es válido');
	        }

        }
        catch (Exception $e) 
        {
            $this->createResponse(500, $e->getMessage());

        }      
	}

	function post_borrar(){
    	$jwt = apache_request_headers()['Authorization'];

        if($this->validateToken($jwt)){

        	$id = $_POST['id'];
       
            $song = Model_Canciones::find($id);

            if($song != null){
              	$song->delete();

              	$this->createResponse(200, 'Canción borrada correctamente', ['song' => $song]);
            }else{
              	$this->createResponse(400, 'La canción no existe');
            }
          
        }else{

            $this->createResponse(400, 'No tienes permiso para realizar esta acción');

        }
    }

	function get_songs(){

        $jwt = apache_request_headers()['Authorization'];

        if($this->validateToken($jwt)){
          
            $songs = Model_Canciones::find('all');

            if($songs != null){
                $this->createResponse(200, 'Canciones devueltas', ['songs' => $songs]);
            }else{
                $this->createResponse(200, 'No hay canciones');
            }

        }else{

          $this->createResponse(400, 'No tienes permiso para realizar esta acción');

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

    function songExists($title, $url_youtube){

        $songs = Model_Canciones::find('all', array(
                  'where' => array(
                      array('titulo', $title),
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

        $userDB = Model_Usuarios::find('all', array(
        'where' => array(
              array('nombre', $username),
              array('contraseña', $password)
            )
        ));

        if($userDB != null){
            return true;
        }else{
            return false;
        }
    }

}