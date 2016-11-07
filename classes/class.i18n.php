<?php
  /**
   * This Class is used for translations of strings and
   * managing the current active language for the site.
   *
   * The Class uses a singleton pattern like approach, where there is an instance
   * created for each used language of a call
   *
   * @author M. Käser
   * @date 10.09.2014
   */
  class I18n {
    private static $instance = null;
    private $folder = null;
    private static $lang = null;
    private static $available_languages = null;
    private static $translations = array();
    private static $folders = array();

    private function __construct($lang) {
      static::$lang = $lang;
      if(!isset(static::$translations[$lang])) {
        static::$translations[$lang] = array();
      }
      static::loadFolders();
    }

    /**
     * Singleton call for creating the instance
     * Creates a new instance if it does not already exist
     *
     * If no language is provided the DEFAULT_LANGAUGE from the config is used
     *
     * @param string $lang - a valid language, from which the instance shall be generated
     * @return I18N $instance - The instance of the language
     */
    public static function instance($lang=null) {
      if($lang == null || !static::validLang($lang))
       $lang = I18N::lang();

      if(static::$instance != null)
        return static::$instance;
      return static::$instance = new I18n($lang);
    }

    /**
     * Returns the current active language and sets the language property
     * inside the Session.
     *
     * @return string $lang - the currently active language
     */
    public static function lang() {
      if(static::$lang)
        return static::$lang;

      if(isset($_REQUEST['lang']) && static::validLang($_REQUEST['lang']))
        return static::$lang = $_SESSION['lang'] = $_REQUEST['lang'];

      if(isset($_SESSION['lang']) && static::validLang($_SESSION['lang']))
        return static::$lang = $_SESSION['lang'];

      $user = UserHandler::instance()->user();
      if(!is_null($user) && static::validLang($user->lang()))
        return static::$lang = $_SESSION['lang'] = $user->lang();

      return static::$lang = $_SESSION['lang'] = DEFAULT_LANGUAGE;
    }

    /**
     * Checks if a language is valid
     *
     * @param string $lang
     * @return bool $valid
     */
    public static function validLang($lang) {
      if(!in_array($lang, static::availableLanguages()))
        return false;
      return true;
    }

    /**
     * Returns the available ranguages of the site
     * The languages are gathered from the config-constant AVAILABLE_LANGUAGES
     */
    public static function availableLanguages() {
      if(is_null(static::$available_languages))
      static::$available_languages = explode(',', AVAILABLE_LANGUAGES);
      return static::$available_languages;
    }

    /**
     * Method is called for translating a specific key.
     * The method additionally searches for the keys in the $args-array
     * and replaces the found markers
     *
     * @param string $key - the key inside the translation file
     * @param assoc-array $args - an array with the replacement values for the translation
     */
    public static function translate($key, $args=array()) {
      $inst = I18N::instance();
      if(!array_key_exists($key, static::$translations[static::$lang]))
        $val = $key;
      else
        $val = static::$translations[static::$lang][$key];

      return Utilities::templateReplace($val, $args);
    }

    /**
     * Adds a Folder to the translation pool and then loads the available language files within
     *
     * @param string $folder - the path of the folder
     */
    public function addFolder($folder) {
      $folder = realpath($folder);

      if(!file_exists($folder))
        throw Exception("The folder $folder does not exist");

      $this->appendFolder(static::$lang, $folder);
      $this->loadLanguageFiles($folder);
    }

    /**
     * Adds a folder to the folder pool if the path is not already inside it or the language is not already there
     *
     * @param string $lang - the language the folder belongs to
     * @param string $folder_path - the complete path to the folder
     */
    private function appendFolder($lang, $folder_path) {
      $found = false;
      foreach(static::$folders as &$folder) {
        if($folder['path'] == $folder_path) {
          if(in_array($lang, $folder['langs']))
            return;

          $found = true;
          $folder['langs'][] = $lang;
        }
      }

      if(!$found)
        static::$folders[] = array('path' => $folder_path, 'langs' => array($lang));
    }

    /**
     * Loads all the folders to the corresponding intance-language
     */
    private static function loadFolders() {
      foreach(static::$folders as $folder)
        if(!in_array(static::$lang, $folder['langs']))
          I18N::instance(static::$lang)->loadLanguageFiles($folder);
    }
    /**
     * Parses the language files inside a folder and appends the
     * translations to the translation-array in the current active instance language
     *
     * @param string $folder - the folderpath in which the language is
     */
    private function loadLanguageFiles($folder) {
      $matches = array();
      $regex = '/("([^"]*)":\s?\n\"([^"]*)"[^"]?)/i';
      $lang_file = $folder . '/' . static::$lang . '.txt';
      $content = Utilities::getFileContent($lang_file);

      preg_match_all($regex, $content, $matches);

      $translations = array();
      if(isset($matches[2]) && isset($matches[3]))
        $translations = array_combine($matches[2], $matches[3]);

      static::$translations[static::$lang] = array_merge(static::$translations[static::$lang], $translations);
    }

  }
  /**
   * Sugar for calling I18N::translate($key, $args)
   *
   * Attention, this calles the current official active Instance language.
   * -> for calling the translation to an other language do it via:
   * I18N::instance(OTHER_LANGUAGE)->translate($key, $args);
   *
   */
  function i($key, $args=array()) {
    return I18N::translate($key, $args);
  }
?>