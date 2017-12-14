<?php

class Model_Roles extends Orm\Model
{

   	protected static $_table_name = 'roles'; 
	protected static $_properties = array('id','tipo'
	   );

	protected static $_has_many = array(
    'usuarios' => array(
        'key_from' => 'id',
        'model_to' => 'Model_Usuarios',
        'key_to' => 'id_rol',
        'cascade_save' => true,
        'cascade_delete' => false,
    )
);

}
