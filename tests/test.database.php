<?php

  class Test_Database  extends BaseTest {
    private $db = null;
    public function prepare() {
      $cwd = getcwd();
      chdir('..');
      require_once('config.php');
      require_once('classes/class.core.php');

      // prevent chdir problems
      $core = Core::instance();
      $this->db = $core->getDb();
      chdir($cwd);
    }

    public function testCreateTables() {
      $db = $this->db;
      $db->createTable('only_table');
      $db->createTable('with_arguments', array(
        array('name', 'VARCHAR(255) NOT NULL'),
        'password TEXT',
      ));
      $db->drop('only_table');
      $db->drop('with_arguments');
      return 'Created Table Only_table and with_arguments';
    }

    public function testInsertNonAssocArray() {
      $db = $this->db;
      $table = 'test_insert_non_assoc';
      $db->createTable($table, array(
        array('name', 'VARCHAR(255) NOT NULL'),
        'password TEXT',
        array('first_name', 'VARCHAR(45) NOT NULL')
      ));
      $db->insert($table, array('ruedi', 'sdf11', 'fff11'), array('name', 'password', 'first_name'));
      $db->insert($table, array('second', 'fff22'), array('name', 'password'));
      $db->insert($table, array('third', 'fff33'), array('name', 'first_name'));
      $db->drop($table);
    }

    public function testInsertAssocArray() {
      $db = $this->db;
      $table = 'test_insert_assoc';
      $db->createTable($table, array(
        array('name', 'VARCHAR(255) NOT NULL'),
        'password TEXT NOT NULL',
        array('first_name', 'VARCHAR(45) NOT NULL')
      ));

      $db->insert($table, array(
          'name' => 'ruedi',
          'password' => 'sdf11',
          'first_name' => 'fff11'
        ),
        array(
          'name',
          'password',
          'first_name'
        ));

      $db->insert($table, array(
          'name' => 'only name!',
          'password' => 'sdf22',
          'first_name' => 'fff22'
        ),
        array(
          'name'
        ));

      $db->insert($table, array(
          'name' => 'no args columns',
          'password' => 'sdf33',
          'first_name' => 'fff33'
        ));

      $db->insert($table, array(
          'name' => 'no args columns only name'
        ));

      $db->drop($table);

    }

  }
?>