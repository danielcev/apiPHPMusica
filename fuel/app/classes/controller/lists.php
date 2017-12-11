<?php

use Firebase\JWT\JWT;

class Controller_Lists extends Controller_Rest{

	private $key = 'my_secret_key';
	protected $format = 'json';

	function post_create()
   	{
   		$jwt = apache_request_headers()['Authorization'];

        try {
            if (!isset($_POST['title']) || $_POST['title'] == "") 
            {

              $this->createResponse(400, 'Parámetros incorrectos');

            }

            $token = JWT::decode($jwt, $this->key, array('HS256'));

        	  $id_user = $token->data->id;
            $title = $_POST['title'];

            if(!$this->listExists($id_user, $title)){

                $props = array('id_user' => $id_user, 'title' => $title);

                $new = new Model_List($props);
                $new->save();

                $this->createResponse(200, 'Lista creada', ['list' => $new]);

            }else{
                $this->createResponse(400, 'Lista ya creada');
            }

        }
        catch (Exception $e) 
        {
            $this->createResponse(500, $e->getMessage());

        }      

   	}

   	function get_lists(){

        $jwt = apache_request_headers()['Authorization'];

        $token = JWT::decode($jwt, $this->key, array('HS256'));

        $id_user = $token->data->id;

        if($this->validateToken($jwt)){
          
          $lists = Model_List::find('all', array(
    		    'where' => array(
        		    array('id_user', $id_user),
    		  )));

          if($lists != null){
            $this->createResponse(200, 'Listas devueltas', ['lists' => $lists]);
          }else{
            $this->createResponse(200, 'No hay listas', ['lists' => null]);
          }

        }else{

          $this->createResponse(400, 'No tienes permiso para realizar esta acción');

        }

    }

    function post_borrar(){
    	$jwt = apache_request_headers()['Authorization'];

        if($this->validateToken($jwt)){
          $id = $_POST['id'];
       
          $list = Model_List::find($id);
          $list->delete();

          $this->createResponse(200, 'Lista borrada correctamente', ['list' => $list]);

        }else{

          $this->createResponse(400, 'No tienes permiso para realizar esta acción');

        }
    }

    function post_edit(){
        $jwt = apache_request_headers()['Authorization'];

        if($this->validateToken($jwt)){
          $id = $_POST['id'];
          $title = $_POST['title'];
       
          $list = Model_List::find($id);
          $list->title = $title;
          $list->save();

          $this->createResponse(200, 'Lista editada', ['list' => $list]);

        }else{

          $this->createResponse(400, 'No tienes permiso para realizar esta acción');

        }
    }

    function get_list(){
        $jwt = apache_request_headers()['Authorization'];

        if($this->validateToken($jwt)){
            $id = $_GET['id'];

            $list = Model_List::find($id);

            if($list != null){

                $this->createResponse(200, 'Lista devuelta', ['list' => $list]);

            }else{

                $this->createResponse(500, 'Error en el servidor');

            }

        }else{

            $this->createResponse(400, 'No tienes permiso para realizar esta acción');

        }

   }

   function listExists($id_user, $title){

      $lists = Model_List::find('all', array(
                  'where' => array(
                      array('id_user', $id_user),
                      array('title', $title)
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

        $userDB = Model_User::find('all', array(
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