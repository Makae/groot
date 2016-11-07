<?php
  /**
   * The core is responsible for instantiating all used
   * main-modules of the application
   *
   * The Core uses the singleton pattern
   *
   * @author M. Käser
   * @date 02.10.2014
   */
  class Core {
    private static $instance = null;
    private $controller;
    private $db;

    /**
     * Loads the correct template diractory in the Template renderer
     * and initiates the PHP-session
     */
    private function __construct() {
      session_start();
      TemplateRenderer::instance()->setTplDir(TEMPLATE_TMP_DIR);
    }

    /**
     * Singleton call for creating the instance
     * Creates a new instance if it does not already exist
     *
     * @return Core $instance - The instance
     */
    public static function instance() {
      if(!is_null(static::$instance))
        return static::$instance;

      static::$instance = new Core();
      static::$instance->postConstruct();
      return static::$instance;
    }

    /**
     * This part has had to be outsourced from the Constructor,
     * because the called Classes access the core and by
     * that the Core::instance() method. Thus an endless recursion
     * occured. because the instance is not already instantiated
     */
    private function postConstruct() {
      $this->db = DB::instance();
      I18N::instance()->addFolder('i18n');

      UserHandler::instance();

      $this->controller = Controller::instance();
    }

    /**
     * Hard redirect via header
     */
    public function redirectAndDie($view, $params=array()) {
      header('Location: ' . WWWROOT . '?' . http_build_query($params));
      die();
    }

    /**
     * Returns the current, correct instance of the db
     *
     * @return DB $instance - the instance of the database
     */
    public function getDb() {
      return $this->db;
    }

    /**
     * Inits the contorller and rendering via a composite-like
     * call-stack.
     *
     * The returned content might be html or a json-string
     *
     * @return string $content - the rendered content returned to the browser
     */
    public function render() {
      $this->controller->init();
      return $this->controller->render();
    }

  }
?>