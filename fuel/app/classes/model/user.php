<?php

class Model_User extends Orm\Model
{

   	protected static $_table_name = 'users'; 
	protected static $_properties = array(
	      'id',
	      'username' => array( 
	         'data_type' => 'varchar'
	      ),
	      'password' => array( 
	         'data_type' => 'varchar'
	      )
	   );

}