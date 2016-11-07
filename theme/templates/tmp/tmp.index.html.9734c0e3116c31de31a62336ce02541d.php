<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="<?php if(isset($WWWCSS)) echo $WWWCSS;?>/styles.css" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,400,300,600,700,800|Roboto:900,400,700,400italic,500,300,100|&subset=latin,latin-ext' rel='stylesheet' type='text/css' />
    <script type="text/javascript" src="<?php if(isset($WWWJS)) echo $WWWJS;?>/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="<?php if(isset($WWWJS)) echo $WWWJS;?>/core.js"></script>
  </head>
  <body>
  <div id="header">
    <div class="container">
     <?php if(isset($header)) echo $header;?>
    </div>
  </div>
  <div id="container" class="container">
    <div id="main">
      <div id="left">
        <?php if(isset($navi)) echo $navi;?>
      </div>
      <div id="right">
        <?php if(isset($breadcrumbs)) echo $breadcrumbs;?>
        <div id="content">
        <?php if(isset($view_content)) echo $view_content;?>
        </div>
      </div>
    </div>
    <div id="footer">
      <?php if(isset($footer)) echo $footer;?>
    </div>
  </div>
  </body>
</html>