<?php
namespace Fuel\Migrations;

class Contener
{

    function up()
    {
        \DBUtil::create_table('contener', array(
            'id_lista' => array('type' => 'int', 'constraint' => 5),
            'id_cancion' => array('type' => 'int', 'constraint' => 5)
        ), array('id_lista', 'id_cancion'),
            true,
            'InnoDB',
            'utf8_unicode_ci',
            array(
                array(
                    'constraint' => 'claveAjenaContenerAListas',
                    'key' => 'id_lista',
                    'reference' => array(
                        'table' => 'listas',
                        'column' => 'id',
                    ),
                    'on_update' => 'CASCADE',
                    'on_delete' => 'CASCADE'
                ),
                array(
                    'constraint' => 'claveAjenaContenerACanciones',
                    'key' => 'id_cancion',
                    'reference' => array(
                        'table' => 'canciones',
                        'column' => 'id',
                    ),
                    'on_update' => 'CASCADE',
                    'on_delete' => 'CASCADE'
                )
            ));
    }

    function down()
    {
       \DBUtil::drop_table('contener');
    }
}