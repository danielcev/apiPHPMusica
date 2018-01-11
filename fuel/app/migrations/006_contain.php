<?php
namespace Fuel\Migrations;

class Contain
{

    function up()
    {
        \DBUtil::create_table('contain', array(
            'id_list' => array('type' => 'int', 'constraint' => 5),
            'id_song' => array('type' => 'int', 'constraint' => 5)
        ), array('id_list', 'id_song'),
            true,
            'InnoDB',
            'utf8_unicode_ci',
            array(
                array(
                    'constraint' => 'claveAjenaContainALists',
                    'key' => 'id_list',
                    'reference' => array(
                        'table' => 'lists',
                        'column' => 'id',
                    ),
                    'on_update' => 'CASCADE',
                    'on_delete' => 'RESTRICT'
                ),
                array(
                    'constraint' => 'claveAjenaContainASongs',
                    'key' => 'id_song',
                    'reference' => array(
                        'table' => 'songs',
                        'column' => 'id',
                    ),
                    'on_update' => 'CASCADE',
                    'on_delete' => 'RESTRICT'
                )
            ));
    }

    function down()
    {
       \DBUtil::drop_table('contain');
    }
}