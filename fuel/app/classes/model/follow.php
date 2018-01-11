<?php

class Model_Follow extends Orm\Model
{

   	protected static $_table_name = 'follow'; 
	protected static $_properties = array('user_followed','user_follower');

}