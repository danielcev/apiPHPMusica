<?php
namespace Fuel\Migrations;

class Canciones
{

    function up()
    {
        \DBUtil::create_table('canciones', array(
            'id' => array('type' => 'int', 'constraint' => 5, 'auto_increment' => true),
            'titulo' => array('type' => 'varchar', 'constraint' => 100),
            'artista' => array('type' => 'varchar', 'constraint' => 100),
            'url_youtube' => array('type' => 'varchar', 'constraint' => 100)

        ), array('id'));

        //Adding UNIQUE constraint to 'url_youtube' column
        \DB::query("ALTER TABLE `canciones` ADD UNIQUE (`url_youtube`)")->execute();
    }

    function down()
    {
       \DBUtil::drop_table('canciones');
    }
}