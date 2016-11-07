<?php
// include your tests here
  include_once('config.php');
  include_once('class.basetest.php');
  BaseTest::loadFolders(unserialize(TEST_FOLDERS));
  include_once('init.php');
?>