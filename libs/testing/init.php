<?php
  header('Content-Type: text/html; charset=utf-8');
  ob_start();
  BaseTest::run();
  $contents = ob_get_contents();
  ob_end_clean();
?>
<html>
  <head>
    <style type="text/css">
      body {
        font-family:Helvetica, Arial, sans-serif;
        font-weight: lighter;
      }

      .indent {
        display: block;
      }
      .indent-1 {
        margin-left: 20px;
      }
      .indent-2 {
        margin-left: 40px;
      }
      .indent-3 {
        margin-left: 60px;
      }

      .ok {
        color: #629C62;
      }
      .error {
        color: #870000;
      }
      .warning {
        color: #FFA500;
      }

      .label {
        display: inline-block;
        text-align: left;
      }
      .label-result {
        margin-right: 0.25em;
      }
      div.results > .indent {
        display: inline-block;
      }
    </style>
  </head>
  <body>
    <p><?= $contents ?></p>
  </body>
</html>