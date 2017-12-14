<?php

class Model_Canciones extends Orm\Model
{

   	protected static $_table_name = 'canciones'; 
	protected static $_properties = array(
	      'id',
	      'titulo',
	      'url_youtube'
	   );

	protected static $_many_many = array(
    'listas' => array(
	        'key_from' => 'id',
	        'key_through_from' => 'id_cancion',
	        'table_through' => 'contener',
	        'key_through_to' => 'id_lista',
	        'model_to' => 'Model_Listas',
	        'key_to' => 'id',
	        'cascade_save' => true,
	        'cascade_delete' => false,
    	)
	);
	
}