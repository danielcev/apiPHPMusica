<?php

class Model_Usuarios extends Orm\Model
{

   	protected static $_table_name = 'usuarios';
	protected static $_properties = array('id','nombre','contraseña','id_rol');

	protected static $_has_many = array(
        'listas' => array(
            'key_from' => 'id',
            'model_to' => 'Model_Listas',
            'key_to' => 'id_usuario',
            'cascade_save' => true,
            'cascade_delete' => true,
        )
    );

	protected static $_belongs_to = array(
    'roles' => array(
        'key_from' => 'id_rol',
        'model_to' => 'Model_Roles',
        'key_to' => 'id',
        'cascade_save' => true,
        'cascade_delete' => true,
    ));

}