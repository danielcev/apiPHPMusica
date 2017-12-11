<?php

class Model_List extends Orm\Model
{

   	protected static $_table_name = 'lists'; 
	protected static $_properties = array(
	      'id',
	      'title' => array( 
	         'data_type' => 'varchar'
	      ),
	      'id_user' => array( 
	         'data_type' => 'varchar'
	      )
	   );

}