<?php
namespace Fuel\Migrations;

class Usuarios
{

    function up()
    {
        \DBUtil::create_table('usuarios', array(
            'id' => array('type' => 'int', 'constraint' => 5, 'auto_increment' => true, 'null' => false),
            'username' => array('type' => 'varchar', 'constraint' => 100, 'null' => false),
            'password' => array('type' => 'varchar', 'constraint' => 100, 'null' => false),
            'email' => array('type' => 'int', 'constraint' => 5, 'null' => false)
        ), array('id')
            );

        //Adding UNIQUE constraint to 'username' column
        \DB::query("ALTER TABLE `usuarios` ADD UNIQUE (`username`)")->execute();
        \DB::query("ALTER TABLE `usuarios` ADD UNIQUE (`email`)")->execute();
    }


    function down()
    {
       \DBUtil::drop_table('usuarios');
    }
}