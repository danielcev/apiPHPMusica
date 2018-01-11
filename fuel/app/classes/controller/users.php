<?php

//require_once '../../../vendor/autoload.php';
use Firebase\JWT\JWT;

class Controller_Users extends Controller_Rest
{
	private $key = 'my_secret_key';
    protected $format = 'json';
 
   function post_create()
   {

        try {
            if (!isset($_POST['username']) || !isset($_POST['password']) || !isset($_POST['email']) || $_POST['username'] == "" || $_POST['password'] == "" || $_POST['email'] == "") 
            {

              $this->createResponse(400, 'Parámetros incorrectos');

            }

            $username = $_POST['username'];
            $password = $_POST['password'];
            $email = $_POST['email'];

            if(!$this->userExists($username, $email)){ //Si el usuario todavía no existe
                $props = array('username' => $username, 'password' => $password, 'email' => $email, 'id_rol' => 2);

                $new = new Model_Users($props);
                $new->save();

                $this->createResponse(200, 'Usuario creado', ['user' => $new]);

            }else{ //Si el usuario introducido ya existe

                $this->createResponse(400, 'El usuario ya existe, username o email repetido');

            } 

       }
        catch (Exception $e) 
        {
            $this->createResponse(500, $e->getMessage());

        }      
        
   }

   function get_login()
   {

    try{

        if (!isset($_POST['username']) || !isset($_POST['password']) || $_POST['username'] == "" || $_POST['password'] == "") {

            $this->createResponse(400, 'Parámetros incorrectos');

        }

     	$username = $_GET['username'];
  	    $password = $_GET['password'];

      	$userDB = Model_Users::find('first', array(
          	'where' => array(
              	array('username', $username),
              	array('password', $password)
          	),
      	));

      	if($userDB != null){ //Si el usuario se ha logueado (existe en la BD)

      		//Creación de token
      		$time = time();
      		$token = array(
      		    'iat' => $time, 
      		    'data' => [ 
                    'id' => $userDB['id'],
      		        'username' => $username,
      		        'password' => $password
      		    ]
      		);

      		$jwt = JWT::encode($token, $this->key);

            $this->createResponse(200, 'login correcto', ['token' => $jwt, 'username' => $username]);

      	}else{

            $this->createResponse(400, 'El usuario no existe');

      	}

    }catch (Exception $e){
        $this->createResponse(500, $e->getMessage());

    }  
}

    function post_borrar(){

        try{
            $jwt = apache_request_headers()['Authorization'];

            if($this->validateToken($jwt)){
                $token = JWT::decode($jwt, $this->key, array('HS256'));
                $id = $token->data->id;
           
                $usuario = Model_Users::find($id);

                if($usuario != null){
                    $usuario->delete();

                    $this->createResponse(200, 'Usuario borrado', ['usuario' => $usuario]);
                }else{
                    $this->createResponse(400, 'El usuario introducido no existe');
                }
              
            }else{
                $this->createResponse(400, 'No tienes permiso para realizar esta acción');

            }

        }catch (Exception $e){

            $this->createResponse(500, $e->getMessage());

        }  
      
    }

    function post_edit(){

        try{
            $jwt = apache_request_headers()['Authorization'];

            if($this->validateToken($jwt)){
                $newPassword = $_POST['password'];
                $token = JWT::decode($jwt, $this->key, array('HS256'));

                $id = $token->data->id;
           
                $usuario = Model_Users::find($id);

                if($usuario != null){
                    $usuario->password = $newPassword;
                    $usuario->save();
                    $this->createResponse(200, 'Usuario editado', ['user' => $usuario]);
                }else{
                    $this->createResponse(400, 'El usuario no existe');
                }
                
            }else{

                $this->createResponse(400, 'No tienes permiso para realizar esta acción');

            }
        }catch (Exception $e){

            $this->createResponse(500, $e->getMessage());

        } 
        
    }

    function userExists($username, $email){

        $userDB = Model_Users::find('all', array(
                    'where' => array(
                        array('username', $username),
                        'or' => array(
                          array('email', $email),
                        ),
                    )
                )); 

        if($userDB != null){
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

    function createResponse($code, $message, $data = []){

        $json = $this->response(array(
              'code' => $code,
              'message' => $message,
              'data' => $data
            ));

        return $json;

    }

}