<?php
namespace Fuel\Migrations;

class Lists
{

    function up()
    {
        \DBUtil::create_table('lists', array(
            'id' => array('type' => 'int', 'constraint' => 5, 'auto_increment' => true, 'null' => false),
            'title' => array('type' => 'varchar', 'constraint' => 100, 'null' => false),
            'editable' => array('type' => 'bool', 'null' => false),
            'id_user' => array('type' => 'int', 'constraint' => 5, 'null' => false)
        ), array('id'),
            true,
            'InnoDB',
            'utf8_unicode_ci',
            array(
                array(
                    'constraint' => 'claveAjenaListsAUsers',
                    'key' => 'id_user',
                    'reference' => array(
                        'table' => 'users',
                        'column' => 'id',
                    ),
                    'on_update' => 'CASCADE',
                    'on_delete' => 'RESTRICT'
                )
            ));
    }

    function down()
    {
       \DBUtil::drop_table('lists');
    }
}