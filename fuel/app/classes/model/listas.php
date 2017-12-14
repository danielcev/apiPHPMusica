<?php

class Model_Listas extends Orm\Model
{

   	protected static $_table_name = 'listas'; 
	protected static $_properties = array(
	      'id',
	      'titulo',
	      'id_usuario'
	   );

	protected static $_many_many = array(
    'canciones' => array(
	        'key_from' => 'id',
	        'key_through_from' => 'id_lista',
	        'table_through' => 'contener',
	        'key_through_to' => 'id_cancion',
	        'model_to' => 'Model_Canciones',
	        'key_to' => 'id',
	        'cascade_save' => true,
	        'cascade_delete' => false,
    	)
	);

	protected static $_belongs_to = array(
    'usuarios' => array(
        'key_from' => 'id_usuario',
        'model_to' => 'Model_Usuarios',
        'key_to' => 'id',
        'cascade_save' => true,
        'cascade_delete' => false,
    ));

	
}