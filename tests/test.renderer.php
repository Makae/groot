<?php

  class Test_Renderer  extends BaseTest {
    private $db = null;

    public function prepare() {
      $cwd = getcwd();
      chdir('..');
      require_once('config.php');
      require_once('classes/class.autoload.php');
      require_once('testdata.php');

      // prevent chdir problems
      $core = Core::instance();
      chdir($cwd);
    }

    public function testLoops() {
      $body = array(
        'content' => array(
          array('1first', '1second', '1third'),
          array('2first', '2second', '2third'),
          array('3first', '3second', '3third')
        )
      );
      $args = array(
        'head' => array('content' => array('first', 'second', 'third')),
        'body' => $body
      );
      $renderer = TemplateRenderer::instance();
      echo "<pre>";
      die(htmlspecialchars($renderer->extendedRender(realpath('../theme/templates/table-test.html'), $args)));
    }

    public function tesRendermenue() {
      $renderer = TemplateRenderer::instance();

      $naviArray[] = array("link" => "index.php?view=home", "icon" => "icon_house_alt", "cls" => 'stdanimation', "label" => "Home" );
      $naviArray[] = array("link" => "index.php?view=profile", "icon" => "icon_profile", "cls" => 'stdanimation', "label" => "Profile" );
      $naviArray[] = array("link" => "index.php?view=categories", "icon" => "icon_tag", "cls" => 'stdanimation', "label" => "Categories" );
      $naviArray[] = array("link" => "index.php?view=shoppingcart", "icon" => "icon_cart", "cls" => 'stdanimation', "label" => "Shopping Cart" );
      $naviArray[] = array("link" => "index.php?view=wishlist", "icon" => "icon_gift", "cls" => 'stdanimation', "label" => "Wishlist" );
      $naviArray[] = array("link" => "index.php?view=hotlist", "icon" => "icon_grid-2x2", "cls" => 'stdanimation', "label" => "Hotlist" );

      $renderer->extendedRender(realpath('../theme/templates/test.html'), array(
        'entries' => $naviArray
      ));
    }

  }
?>