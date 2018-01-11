<?php

use Firebase\JWT\JWT;

class Controller_Lists extends Controller_Rest{

	private $key = 'my_secret_key';
	protected $format = 'json';

	function post_create()
   	{
   		
        try {

            $jwt = apache_request_headers()['Authorization'];

            if (empty($_POST['titulo'])) 
            {

                $this->createResponse(400, 'Parámetros incorrectos');

            }else{
                if($this->validateToken($jwt)){
                    $token = JWT::decode($jwt, $this->key, array('HS256'));
                    $id_usuario = $token->data->id;
                    $titulo = $_POST['titulo'];

                    if(!$this->listExists($id_usuario, $titulo)){

                        $props = array('id_usuario' => $id_usuario, 'titulo' => $titulo);

                        $new = new Model_Lists($props);
                        $new->save();

                        $this->createResponse(200, 'Lista creada', ['list' => $new]);

                    }else{
                        $this->createResponse(400, 'Lista ya creada por este usuario');
                    }

                }else{
                  $this->createResponse(400, 'El token no es válido');
                }
            }   
	  
        }
        catch (Exception $e) 
        {
            $this->createResponse(500, $e->getMessage());

        }      

   	}

   	function get_lists(){

        try{
            $jwt = apache_request_headers()['Authorization'];

            if($this->validateToken($jwt)){

              $token = JWT::decode($jwt, $this->key, array('HS256'));
              $id_usuario = $token->data->id;
              
              $lists = Model_Lists::find('all', array(
                    'where' => array(
                        array('id_usuario', $id_usuario),
                  )));

              if($lists != null){
                $this->createResponse(200, 'Listas devueltas', ['lists' => $lists]);
              }else{
                $this->createResponse(200, 'No hay listas', ['lists' => null]);
              }

            }else{

              $this->createResponse(400, 'No tienes permiso para realizar esta acción');

            }
        }catch (Exception $e) {
            $this->createResponse(500, $e->getMessage());

        }  

    }

    function post_borrar(){

        try{
            $jwt = apache_request_headers()['Authorization'];

            if($this->validateToken($jwt)){
                $token = JWT::decode($jwt, $this->key, array('HS256'));
                $id = $_POST['id'];
           
                $list = Model_Lists::find('first', array(
                    'where' => array(
                        array('id', $id),
                        array('id_usuario', $token->data->id)
                    )
                ));

                if ($list != null){
                    $list->delete();

                    $this->createResponse(200, 'Lista borrada correctamente', ['list' => $list]);
                }else{

                    $this->createResponse(400, 'No puedes realizar esta acción');
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
                $id_usuario = $token->data->id;

                $id = $_POST['id'];
                $titulo = $_POST['titulo'];

                $list = Model_Lists::find('first', array(
                    'where' => array(
                        array('id', $id),
                        array('id_usuario', $id_usuario)
                    )
                ));

                if($list != null){
                    $list->titulo = $titulo;
                    $list->save();

                    $this->createResponse(200, 'Lista editada', ['list' => $list]);
                }else{
                    $this->createResponse(400, 'No puedes realizar esta acción');
                }

            }else{

                $this->createResponse(400, 'No tienes permiso para realizar esta acción');

            }
        }catch (Exception $e) {
            $this->createResponse(500, $e->getMessage());

        }
        
    }

   function post_addSong(){

        try{

            $jwt = apache_request_headers()['Authorization'];

            if($this->validateToken($jwt)){
                $id_cancion = $_POST['id_cancion'];
                $id_lista = $_POST['id_lista'];

                $contener = Model_Contain::find('first', array(
                    'where' => array(
                        array('id_lista', $id_lista),
                        array('id_cancion', $id_cancion)
                    )
                ));

                if($contener == null){
                    $token = JWT::decode($jwt, $this->key, array('HS256'));
                    $id_usuario = $token->data->id;

                    $list = Model_Lists::find('first', array(
                        'where' => array(
                            array('id', $id_lista),
                            array('id_usuario', $id_usuario)
                        )
                    ));

                    if($list != null){

                        $props = array('id_cancion' => $id_cancion, 'id_lista' => $id_lista);

                        $new = new Model_Contain($props);
                        $new->save();

                        $this->createResponse(200, 'Canción añadida a la lista', ['list' => $list]);
                        
                    }else{

                        $this->createResponse(400, 'No tienes permiso para añadir canciones a esa lista');

                    }
                }else{
                     $this->createResponse(400, 'La canción ya pertenece a la lista');
                }

            }else{

                $this->createResponse(400, 'No tienes permiso para realizar esta acción');

            }

        }catch(Exception $e){
            $this->createResponse(500, $e->getMessage());
        }
   }

   function listExists($id_usuario, $titulo){

      $lists = Model_Lists::find('all', array(
                  'where' => array(
                      array('id_usuario', $id_usuario),
                      array('titulo', $titulo)
                )));

      if($lists != null){
            return true;
      }else{
            return false;
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