<?php
  /**
   * Baseclass for all Tests. When an a childclass are then added
   * to the static children list of the abstract base class.
   * it subclass need to habe test[NAME] functions which are automatically
   * called by the Base class
   * @author: M. Käser
   * @date: 25. 10. 2014
   */
  abstract class BaseTest {
    const CMP_E = 1;
    const CMP_NE = 2;
    const CMP_L = 3;
    const CMP_LE = 4;
    const CMP_GE = 5;
    const CMP_G = 6;
    const CMP_NL = 7;

    const STATE_OK = 1;
    const STATE_ERROR = 2;

    private static $children = array();
    private static $state = 1;
    private static $tests = 0;

    private static $cmp_chars = array(
      BaseTest::CMP_E => 'equals',
      BaseTest::CMP_NE => 'not equals',
      BaseTest::CMP_L => '<',
      BaseTest::CMP_LE => '<=',
      BaseTest::CMP_GE => '>=',
      BaseTest::CMP_G => '>',
      BaseTest::CMP_NL => 'is null'
    );

    public function __construct() {
      $child = get_class($this);
      if(in_array($child, self::$children))
        return;
      self::$children[] = $this;
    }

    protected function equal($first, $second, $msg) {
      return $this->assert($first, $second, $msg, BaseTest::CMP_E);
    }

    protected function equals($first, $second, $msg) {
      return $this->equal($first, $second, $msg);
    }

    protected function notEqual($first, $second, $msg) {
      return $this->assert($first, $second, $msg, BaseTest::CMP_NE);
    }

    protected function notEquals($first, $second, $msg) {
      return $this->notEqual($first, $second, $msg);
    }

    protected function greater($first, $second, $msg) {
      return $this->assert($first, $second, $msg, BaseTest::CMP_G);
    }

    protected function greaterEqual($first, $second, $msg) {
      return $this->assert($first, $second, $msg, BaseTest::CMP_GE);
    }

    protected function lesser($first, $second, $msg) {
      return $this->assert($first, $second, $msg, BaseTest::CMP_L);
    }

    protected function lesserEqual($first, $second, $msg) {
      return $this->assert($first, $second, $msg, BaseTest::CMP_LE);
    }

    protected function assert($first, $second, $msg, $cmp) {
      self::$tests++;
      switch($cmp) {
        case BaseTest::CMP_E:
          if($first === $second)
            return true;
        break;

        case BaseTest::CMP_G:
          if($first > $second)
            return true;
        break;

        case BaseTest::CMP_GE:
          if($first >= $second)
            return true;
        break;

        case BaseTest::CMP_L:
          if($first < $second)
            return true;
        break;

        case BaseTest::CMP_LE:
          if($first <= $second)
            return true;
        break;

        case BaseTest::CMP_NE:
          if($first != $second)
            return true;
        break;

      }
      self::$state = BaseTest::STATE_ERROR;
      echo BaseTest::_indent($msg . ':', 2);
      echo BaseTest::_indent($first . ' <strong>' . self::$cmp_chars[$cmp] . '</strong> ' . $second, 3);
      return $msg;
    }

    private static function loadClasses() {
      $clazzes = get_declared_classes();
      foreach($clazzes as $cls) {
        if(isset($_REQUEST['test']) && strtolower($cls) != 'test_' . $_REQUEST['test'])
          continue;

        if(!preg_match('/^Test_[a-zA-Z0-9_]*$/', $cls))
          continue;
        new $cls();
      }
    }

    public static function run() {
      self::loadClasses();
      $total = 0;
      $failed = 0;
      $errors = 0;
      echo BaseTest::_indent('Starting testing phase:');
      $cwd = getcwd();
      foreach(self::$children as $instance) {
        chdir(static::classFolder($instance));
        $instance->prepare();
        $methods = get_class_methods($instance);
        foreach($methods as $method) {
          if(!preg_match('/^test[a-zA-Z0-9_]*$/', $method))
            continue;

          static::$state = BaseTest::STATE_OK;
          static::$tests = 0;

          echo BaseTest::_indent('Running test "' . get_class($instance) . '->' . $method . '"<br />', 1);
          try {
            $msg = call_user_method($method, $instance);
            if(is_string($msg))
              echo BaseTest::_indent('<span class="message">Got Message ' . $msg . ' </span><br />', 2);

            if(static::$state == BaseTest::STATE_OK)
              echo BaseTest::_indent('<span class="ok">Sub tests passed (' . static::$tests . ' tests ran)</span><br />', 1);
            else {
              $failed += static::$tests;
              echo BaseTest::_indent('<span class="warning">Sub tests not passed. (' . static::$tests . ' tests ran)</span><br />', 1);
            }
            $total += static::$tests;
          } catch (Exception $e) {
            static::$tests++; // Was not increased due to error
            $total += static::$tests;
            $errors += static::$tests;
            echo BaseTest::_indent('<span class="error">Error while testing. Msg:' . $e->getMessage() . ' (' . static::$tests . ' tests ran)</span><br />', 1);
            continue;
          }
          echo '<br />';
        }
        $instance->finish();
      }
      chdir($cwd);
      echo '<div class="results">';
      echo 'Testing finished, a total of <strong>' . $total . '</strong> tests ran: <br />';
      echo BaseTest::_indent('<span class="label label-result">Errors:</span><span class="error">' . $errors . '</span><br />', 1);
      echo BaseTest::_indent('<span class="label label-result">Fails:</span><span class="warning">' . $failed . '</span><br />', 1);
      echo BaseTest::_indent('<span class="label label-result">Passed:</span><span class="ok">' . ($total - $errors - $failed) . '</span><br />', 1);
      echo '</div>';
    }

    private static function _indent($msg, $indents=0) {
      return '<span class="indent indent-' . $indents . '">' . $msg . '</span>';
    }

    public static function loadFolders($folders) {
      $cwd = getcwd();
      foreach($folders as $folder) {
        $real_path = realpath($folder);
        if(!file_exists($real_path) || !is_dir($real_path))
          continue;
        chdir($real_path);
        $files = glob('test.*.php');
        foreach($files as $file)
          if(file_exists($file))
            require_once($file);
      }
      chdir($cwd);
    }

    private static function classFolder($instance) {
      $reflector = new ReflectionClass(get_class($instance));
      return dirname($reflector->getFileName());
    }

    public function finish() {

    }

    public function prepare() {

    }


  }
?>