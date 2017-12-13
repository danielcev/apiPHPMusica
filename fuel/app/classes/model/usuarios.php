<?php

class Model_Usuarios extends Orm\Model
{

   	protected static $_table_name = 'usuarios';
	protected static $_properties = array('id','nombre',/*'email',*/'contraseña',/*'id_dispositivo','coordenadas','descripcion','ubicacion','cumpleaños',*/'id_rol');

	protected static $_has_many = array(
    'listas' => array(
        'key_from' => 'id',
        'model_to' => 'Model_Listas',
        'key_to' => 'id_usuario',
        'cascade_save' => true,
        'cascade_delete' => true,
    ),
    'noticias' => array(
        'key_from' => 'id',
        'model_to' => 'Model_Noticias',
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
    );

    protected static $_many_many = array(
    'usuarios' => array(
	        'key_from' => 'id',
	        'key_through_from' => 'usuario_sigue',
	        'table_through' => 'seguir',
	        'key_through_to' => 'usuario_seguido',
	        'model_to' => 'Model_Usuarios',
	        'key_to' => 'id',
	        'cascade_save' => true,
	        'cascade_delete' => true,
    	),
    'usuarios' => array(
	        'key_from' => 'id',
	        'key_through_from' => 'usuario_seguido',
	        'table_through' => 'seguir',
	        'key_through_to' => 'usuario_sigue',
	        'model_to' => 'Model_Usuarios',
	        'key_to' => 'id',
	        'cascade_save' => true,
	        'cascade_delete' => true,
    	)
	);

}