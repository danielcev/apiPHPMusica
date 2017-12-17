<?php

class Model_Usuarios extends Orm\Model
{

   	protected static $_table_name = 'usuarios';
	protected static $_properties = array('id','username','password','email');

	protected static $_has_many = array(
        'listas' => array(
            'key_from' => 'id',
            'model_to' => 'Model_Listas',
            'key_to' => 'id_usuario',
            'cascade_save' => true,
            'cascade_delete' => true,
        )
    );

}