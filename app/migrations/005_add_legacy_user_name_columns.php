<?php

class Add_legacy_user_name_columns
{
    private $_lava;

    public function __construct()
    {
        $this->_lava = lava_instance();
        $this->_lava->call->dbforge();
    }

    public function up()
    {
        if (!$this->_lava->dbforge->table_exists('users')) {
            throw new RuntimeException('The users table must exist before adding name columns.');
        }

        foreach (['firstname', 'lastname'] as $column) {
            if (!$this->_lava->dbforge->column_exists('users', $column)) {
                $this->_lava->dbforge->add_column('users', [
                    $column => [
                        'type' => 'VARCHAR',
                        'constraint' => 100,
                        'null' => FALSE,
                        'default' => '',
                    ],
                ]);
            }
        }
    }

    public function down()
    {
        foreach (['lastname', 'firstname'] as $column) {
            if ($this->_lava->dbforge->column_exists('users', $column)) {
                $this->_lava->dbforge->drop_column('users', $column);
            }
        }
    }
}