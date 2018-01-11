<?php

class Model_Songs extends Orm\Model
{

   	protected static $_table_name = 'songs'; 
	protected static $_properties = array(
	      'id',
	      'title',
	      'artist',
	      'url_youtube',
	      'reproductions'
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