<?php
  include_once('config.php');
  include_once('classes/class.autoload.php');


  $core = Core::instance();

  // The testdata file contains default data
//  require_once('testdata.php');

  echo $core->render();
?>