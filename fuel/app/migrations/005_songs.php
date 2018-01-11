<?php
namespace Fuel\Migrations;

class Songs
{

    function up()
    {
        \DBUtil::create_table('songs', array(
            'id' => array('type' => 'int', 'constraint' => 5, 'auto_increment' => true, 'null' => false),
            'title' => array('type' => 'varchar', 'constraint' => 100, 'null' => false),
            'artist' => array('type' => 'varchar', 'constraint' => 100, 'null' => false),
            'url_youtube' => array('type' => 'varchar', 'constraint' => 100, 'null' => false),
            'reproductions' => array('type' => 'int', 'constraint' => 20, 'null' => false)

        ), array('id'));

        //Adding UNIQUE constraint to 'url_youtube' column
        \DB::query("ALTER TABLE `songs` ADD UNIQUE (`url_youtube`)")->execute();
    }

    function down()
    {
       \DBUtil::drop_table('songs');
    }
}