<?php

  class Test_User  extends BaseTest {
    private $db = null;

    public function prepare() {
      $cwd = getcwd();
      chdir('..');
      require_once('config.php');
      require_once('classes/class.core.php');
      require_once('testdata.php');

      // prevent chdir problems
      $core = Core::instance();
      chdir($cwd);
    }

    public function testLoginLogout() {
      $uh = UserHandler::instance();
      $ok = $uh->login('tony', '12345');
      $user = $uh->user();
      $this->equal($ok, true, 'Could not login user');
      $this->equal($user->getValue('user_name'), 'tony', 'not the right user');
      $this->notEqual($user->getValue('password'), '12345', 'password is not hashed');
      $this->equals($uh->loggedin(), true, 'user should be logged in');

      $uh->logout();
      $this->equals($uh->loggedin(), false, 'user should now be logged out');
    }

    public function testRegister() {
      $uh = UserHandler::instance();
      $uh->register('widow', '12345', 'Black', 'Widow');
      $user = $uh->user();
      $this->equal($user->getValue('user_name'), 'widow', 'Wrong user name');
      $double_register = $uh->register('widow', '12345', 'Black', 'Widow');
      $this->equal($double_register, UserHandler::ERROR_EXISTS, 'Not recognized double registration');
    }

  }
?>