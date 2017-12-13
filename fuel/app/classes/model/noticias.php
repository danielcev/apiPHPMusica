<?php

class Model_Noticias extends Orm\Model
{

   	protected static $_table_name = 'noticias'; 
	protected static $_properties = array('id','descripcion', 'id_usuario'
	   );

	protected static $_belongs_to = array(
    'usuarios' => array(
        'key_from' => 'id_usuario',
        'model_to' => 'Model_Usuarios',
        'key_to' => 'id',
        'cascade_save' => true,
        'cascade_delete' => true,
    );

}