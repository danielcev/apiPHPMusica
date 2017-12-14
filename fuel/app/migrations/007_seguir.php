<?php
namespace Fuel\Migrations;

class Seguir
{

    function up()
    {
        \DBUtil::create_table('seguir', array(
            'usuario_sigue' => array('type' => 'int', 'constraint' => 5),
            'usuario_seguido' => array('type' => 'int', 'constraint' => 5)
        ), array('usuario_sigue', 'usuario_seguido'),
            true,
            'InnoDB',
            'utf8_unicode_ci',
            array(
                array(
                    'constraint' => 'claveAjenaSeguirAUsuariosSigue',
                    'key' => 'usuario_sigue',
                    'reference' => array(
                        'table' => 'usuarios',
                        'column' => 'id',
                    ),
                    'on_update' => 'CASCADE',
                    'on_delete' => 'CASCADE'
                ),
                array(
                    'constraint' => 'claveAjenaSeguirAUsuariosSeguido',
                    'key' => 'usuario_seguido',
                    'reference' => array(
                        'table' => 'usuarios',
                        'column' => 'id',
                    ),
                    'on_update' => 'CASCADE',
                    'on_delete' => 'CASCADE'
                )
            ));
    }

    function down()
    {
       \DBUtil::drop_table('seguir');
    }
}