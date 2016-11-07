<?php
  interface IViewlet {
    static function name();
    function process($data);
    function render();
  }
?>