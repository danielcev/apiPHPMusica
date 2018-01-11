<?php
namespace Fuel\Migrations;

class Users
{

    function up()
    {
        \DBUtil::create_table('users', array(
            'id' => array('type' => 'int', 'constraint' => 5, 'auto_increment' => true, 'null' => false),
            'username' => array('type' => 'varchar', 'constraint' => 100, 'null' => false),
            'password' => array('type' => 'varchar', 'constraint' => 100, 'null' => false),
            'email' => array('type' => 'varchar', 'constraint' => 100, 'null' => false),
            'city' => array('type' => 'varchar', 'constraint' => 100, 'null' => false),
            'description' => array('type' => 'varchar', 'constraint' => 100, 'null' => false),
            'birthday' => array('type' => 'varchar', 'constraint' => 100, 'null' => false),
            'id_device' => array('type' => 'varchar', 'constraint' => 100, 'null' => false),
            'photo' => array('type' => 'varchar', 'constraint' => 100, 'null' => false),
            'x' => array('type' => 'decimal', 'constraint' => 50, 'null' => false),
            'y' => array('type' => 'decimal', 'constraint' => 50, 'null' => false),
            'id_rol' => array('type' => 'int', 'constraint' => 5, 'null' => false),
            'id_privacity' => array('type' => 'int', 'constraint' => 5, 'null' => false)
        ), array('id'),
            true,
            'InnoDB',
            'utf8_unicode_ci',
            array(
                array(
                    'constraint' => 'claveAjenaUsersARol',
                    'key' => 'id_rol',
                    'reference' => array(
                        'table' => 'roles',
                        'column' => 'id',
                    ),
                    'on_update' => 'CASCADE',
                    'on_delete' => 'RESTRICT'
                ),
                array(
                    'constraint' => 'claveAjenaUsersAPrivacity',
                    'key' => 'id_privacity',
                    'reference' => array(
                        'table' => 'privacity',
                        'column' => 'id',
                    ),
                    'on_update' => 'CASCADE',
                    'on_delete' => 'RESTRICT'
                )
            ));

        //Adding UNIQUE constraint to 'username' column
        \DB::query("ALTER TABLE `users` ADD UNIQUE (`username`)")->execute();
        \DB::query("ALTER TABLE `users` ADD UNIQUE (`email`)")->execute();
    }


    function down()
    {
       \DBUtil::drop_table('users');
    }
}