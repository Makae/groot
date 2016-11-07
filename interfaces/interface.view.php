<?php
  interface IView {
    function name();
    function viewletMainMenu();
    function viewletNavi();
    function viewletFooter();
    function process();
    function render();
  }
?>