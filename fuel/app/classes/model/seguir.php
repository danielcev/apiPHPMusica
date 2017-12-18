<?php

class Model_Seguir extends Orm\Model
{

   	protected static $_table_name = 'seguir'; 
	protected static $_properties = array('usuario_sigue','usuario_seguido');

}