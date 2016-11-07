<?php
  class GrootBreadcrumbsViewlet implements IViewlet {

    private static $path = array();
    public static $enabled = true;

    public static function name() {
      return 'breadcrumbs';
    }

    public static function pushPath($link, $name) {
      array_push(static::$path, array('link' => $link, 'name' => $name));
    }

    public static function popPath() {
      return array_pop(static::$path);
    }

    public function process($config) {
      $controller = Controller::instance();
      $view_key = $controller->getViewKey();
      static::pushPath($controller->getViewUrl('home'), i('view_label_home'));
      if($view_key != 'home')
        static::pushPath($controller->getViewUrl(), i('view_label_' . $view_key));
    }

    public function render() {
      if(!static::$enabled)
        return '';
      $html = '';
      foreach(static::$path as $folder)
        $html .= ($html =='' ? '' : '<span class="arrow_carrot-right arrow"></span>') . '<a href="' . $folder['link'] . '" target="_self">' . $folder['name'] . '</a>';

      return '<div class="breadcrumb">' . i('Your are here:') . ' ' . $html . '</div>';
    }


  }
?>