<?php

use Firebase\JWT\JWT;

class Controller_Songs extends Controller_Rest{

	private $key = 'my_secret_key';
	protected $format = 'json';

	function post_create(){
		$jwt = apache_request_headers()['Authorization'];

        try {
            if (!isset($_POST['titulo']) || $_POST['titulo'] == "" || $_POST['url_youtube'] == "" || !isset($_POST['url_youtube']) || $_POST['artista'] == "" || !isset($_POST['artista'])) 
            {

                $this->createResponse(400, 'Parámetros incorrectos');

            }

            if($this->validateToken($jwt)){

	            	$titulo = $_POST['titulo'];
	            	$url_youtube = $_POST['url_youtube'];
                    $artista = $_POST['artista'];

	            if(!$this->songExists($url_youtube)){

	                $props = array('titulo' => $titulo, 'url_youtube' => $url_youtube, 'artista' => $artista);

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
        try{
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
        }catch (Exception $e) {
            $this->createResponse(500, $e->getMessage());

        }
    	
    }

	function get_songs(){

        try{
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
        }catch (Exception $e) {
            $this->createResponse(500, $e->getMessage());

        }

    }

    function post_edit(){

        try{
            $jwt = apache_request_headers()['Authorization'];

            if($this->validateToken($jwt)){

                if(!isset($_POST['id'])){
                    $this->createResponse(400, 'Es necesario el parámetro id');
                }else{
                    $id = $_POST['id'];
                    $song = Model_Canciones::find($id);

                    if($song == null){
                        $this->createResponse(400, 'id incorrecto, la canción no existe');
                    }else{
                        if (empty($_POST['titulo']) && empty($_POST['url_youtube']) && empty($_POST['artista']) ){

                            $this->createResponse(400, 'Parámetros incorrectos');

                        }else{

                            if (!empty($_POST['titulo'])){
                                $song->titulo = $_POST['titulo'];  
                            }

                            if (!empty($_POST['url_youtube'])){
                                $song->url_youtube = $_POST['url_youtube'];
                            }

                            if (!empty($_POST['artista'])){
                                $song->artista = $_POST['artista'];
                            }

                            $song->save();

                            $this->createResponse(200, 'Canción modificada',['song' => $song]);
                        }

                    }
 
                }
   
            }else{

              $this->createResponse(400, 'No tienes permiso para realizar esta acción');

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

        $songs = Model_Canciones::find('all', array(
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

        $userDB = Model_Usuarios::find('all', array(
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