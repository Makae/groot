<?php
  /**
   * The controller decides which views are called and manages the data
   * which is sent from the views to the viewlets.
   *
   * The Controller uses the singleton pattern
   *
   * @author M. Käser
   * @date 02.10.2014
   */
  class Controller {
    private static $instance = null;
    private $view_key = 'home';
    private $view = null;
    private $viewlets = array();

    private function __construct() {}

    /**
     * Singleton call for creating the instance
     * Creates a new instance if it does not already exist
     *
     * @return Controller $instance - The instance
     */
    public function instance() {
      if(self::$instance != null)
        return self::$instance;

      self::$instance = new Controller();
      self::$instance->postConstruct();
      return self::$instance;
    }

    /**
     * Loads the viewlets from the viewlet-folder
     */
    private function postConstruct() {
      $files = glob('viewlets/viewlet.*.php');
      foreach($files as $file) {

        $matches = array();
        //preg_match takes the variable $matches by reference and alters it inside the function body
        if(!preg_match('/viewlet\.(.+)\.php$/', $file, $matches))
          continue;

        $cls = 'Groot' . $matches[1] . 'Viewlet';
        if(class_exists($cls))
          $this->viewlets[$cls::name()] = new $cls();

      }
    }

    /**
     * Decides which view shall be called and initializes the view
     * the process method is then called in which the view can
     * process the data. Eg. Save new entry, replace data etc.
     */
    public function init() {
      $this->view_key = isset($_REQUEST['view']) ? $_REQUEST['view'] : $this->view_key;
      $cls = 'Groot' . ucfirst($this->view_key) . 'View';

      if(!class_exists($cls))
        throw new Exception('The view "' . $this->view_key . '" does not exist');

      // instantiate the view
      $this->view = new $cls();
      $this->view->process();

      if(!$this->isAjaxRequest())
        $this->_processViewlets();

    }

    /**
     * This method gets the config data for each viewlet via the defined
     * functions in the current view.
     *
     * As example the view GrootShopView
     * can display other submenu entries than the GrootHomeView
     */
    private function _processViewlets() {
      foreach($this->viewlets as $viewlet) {
        $name = $viewlet->name();
        $fn = 'viewlet' . ucfirst($name); // function which is called on th view
        $call_pair = array($this->view, $fn);
        $val = null;
        if(is_callable($call_pair))
          $val = call_user_func_array($call_pair, array());

        $viewlet->process($val);
      }
    }

    /**
     * Method for rendering the ajax content.
     *
     * It is called, when the request-key ajax=1 is provided
     * It is important, that additionally the ajax_fn-Key is provided
     * which specifies the to be called method of the associated view-key
     * Eg. Calling GrootSearchView::ajaxAutocomplete requires the url
     * index.php?view=search&ajax=1%ajax_fn=autocomplete
     */
    private function renderAjax() {
      $fn = isset($_REQUEST['ajax_fn']) ? 'ajax' . ucfirst($_REQUEST['ajax_fn']) : null;
      $call_pair = array($this->view, $fn);

      if($fn == null)
        throw new Exception("No Ajax-method defined which shall be called");

      if(!is_callable($call_pair))
        throw new Exception("The called method '". get_class($this->view) . "::$fn' does not exist");

      return call_user_func_array($call_pair, array());

    }

    /**
     * Calls the render method of the Current view
     * and renders the main page with tha data from
     * the viewlets.
     * This method sets the header of the page.
     * - text/json for ajax requests
     * - text/html for normal requests
     *
     */
    public function render() {
      if($this->isAjaxRequest()) {
        header('Content-Type: text/json; charset=utf-8');
        $response = $this->renderAjax();
        return $response;
      }


      $args = array(
        'WWWROOT' => WWWROOT,
        'WWWTHEME' => WWWTHEME,
        'WWWCSS' => WWWCSS,
        'WWWJS' => WWWJS,
        'view_content' => $this->view->render()
      );

      foreach($this->viewlets as $key => $viewlet)
        $args[$key] = $viewlet->render();

      header('Content-Type: text/html; charset=utf-8');
      return TemplateRenderer::instance()->extendedRender('theme/templates/index.html', $args);
    }

    /**
     * Returns the current-View instance.
     *
     * @return ConcreteView $view - the current view instance
     */
    public function getView() {
      return $this->view;
    }

    /**
     * Returns the current view-Key provided via the Request-Parameter
     *
     * @return string $view_key
     */
    public function getViewKey() {
      return $this->view_key;
    }

    /**
     * Sugor vor composing an url to an view
     *
     * @param mixed $view_key - view_key: to be included in the url | null: the current view_key is taken
     * @return string $view_url - url to the provided view_key
     */
    public function getViewUrl($view_key=null) {
      $view_key = is_null($view_key) ? $this->view_key : $view_key;
      return WWWROOT . '?view=' . $view_key;
    }

    /**
     * Checks if the current request is an ajax request
     *
     * It does this by checking the ajax-parameter
     *
     * $return boolean $is_ajax_request
     */
    public function isAjaxRequest() {
      if(isset($_REQUEST['ajax']) && $_REQUEST['ajax'] == 1)
        return true;
      return false;
    }

  }


?>