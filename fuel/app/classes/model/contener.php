<?php

class Model_Contener extends Orm\Model
{

   	protected static $_table_name = 'contener'; 
	protected static $_properties = array('id_cancion','id_lista');
	protected static $_primary_key = array('id_cancion','id_lista');

}