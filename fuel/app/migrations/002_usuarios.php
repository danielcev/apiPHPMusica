<?php
namespace Fuel\Migrations;

class Usuarios
{

    function up()
    {
        \DBUtil::create_table('usuarios', array(
            'id' => array('type' => 'int', 'constraint' => 5, 'auto_increment' => 'true'),
            'nombre' => array('type' => 'varchar', 'constraint' => 100),
            'email' => array('type' => 'varchar', 'constraint' => 100),
            'contraseña' => array('type' => 'varchar', 'constraint' => 100),
            'id_dispositivo' => array('type' => 'varchar', 'constraint' => 100),
            'coordenadas' => array('type' => 'varchar', 'constraint' => 100),
            'descripcion' => array('type' => 'varchar', 'constraint' => 100),
            'ubicacion' => array('type' => 'varchar', 'constraint' => 100),
            'cumpleaños' => array('type' => 'varchar', 'constraint' => 100),
            'id_rol' => array('type' => 'int', 'constraint' => 5)
        ), array('id'),
            true,
            'InnoDB',
            'utf8_unicode_ci',
            array(
                array(
                    'constraint' => 'claveAjenaUsuariosARoles',
                    'key' => 'id_rol',
                    'reference' => array(
                        'table' => 'roles',
                        'column' => 'id',
                    ),
                    'on_update' => 'CASCADE',
                    'on_delete' => 'CASCADE'
                )
            ));
    }

    function down()
    {
       \DBUtil::drop_table('usuarios');
    }
}