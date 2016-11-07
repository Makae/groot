<?php

  class Test_Models  extends BaseTest {
    private $db = null;
    public function prepare() {
      $cwd = getcwd();
      chdir('..');
      require_once('config.php');
      require_once('classes/class.core.php');

      // prevent chdir problems
      $core = Core::instance();
      chdir($cwd);
    }

    public function testCreateTables() {
      $book = new BookModel(null, array(
        'name' => 'Moinruich',
        'isbn' => 'Iäsbe-ähn'
      ));
      echo "<pre>";
      var_dump($book->getData());
      $book->update();
      var_dump($book->getData());
      return 'Created Table Only_table and with_arguments';
    }

  }
?>