<?php

class Model_Users extends Orm\Model
{

   	protected static $_table_name = 'users';
	protected static $_properties = array('id','username','password','email','id_device'
        ,'photo','x','y','birthday','city','description','id_rol','id_privacity');

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