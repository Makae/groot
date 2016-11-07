<?php
  /**
   * The autloader automatically loads a required class or interface
   * if the class has not been found.
   *
   * The Class is build up generically and can be included in other Projects
   * as well. Just set the AUTOLOAD_FOLDERS accordingly
   *
   * @author: M. Käser
   * @date:   17.11.2014
   */
  class Autoload {
    private static $folders = array();

    public static function load($cls) {
      foreach(static::$folders as $folder) {
        if(preg_match($folder['match'], $cls)) {
          $file = preg_replace($folder['search'], $folder['replace'], $cls);

          if(file_exists($folder['path'] .'/' . strtolower($file))) {
            require_once($folder['path'] .'/' . strtolower($file));
            return;
          }
        }
      }
    }

    /**
     * Adds a folder to the possible pathes of the autoloader
     *
     * @param path - Path of the folder from the root directory
     * @param match - Regex which checks if the called class matches the pattern
     * @param search - Search-Regex for the file-string
     * @param replace - Replace-Pattern for the file-stri*ng
     */
    public static function addFolder($path, $match, $search, $replace) {
      Autoload::$folders[] = array('path' => $path,
                                   'match' => $match,
                                   'search' => $search,
                                   'replace' => $replace);
    }
  }

  // Register the autoload method in PHP
  spl_autoload_register('Autoload::load');
  // load the Autoload_folders which are defined in your config file
  if(defined('AUTOLOAD_FOLDERS')) {
    $autoload_folders = unserialize(AUTOLOAD_FOLDERS);
    foreach($autoload_folders as $folder)
      Autoload::addFolder($folder[0], $folder[1], $folder[2], $folder[3]);
  }

/*
  EXAMPLE:

  @desc: Define the folders and class patterns. This is sent to the autoloader which decides which file to
         include
  @param1: Path of the folder from the root directory
  @param2: Regex which checks if the called class matches the pattern
  @param3: Search-Regex for the file-string
  @param4: Replace-Pattern for the file-string
*/
// define('AUTOLOAD_FOLDERS', serialize(array(
//   array('interfaces',     '/I[A-Z][a-z]+/',     '/^I([A-Z][a-z]+)$/',   'interface.$1.php'),
//   array('classes',        '/.*/',               '/^(.*)$/',             'class.$1.php'),
//   array('classes/models', '/^.*Model$/',        '/^(.*)Model$/',        'class.$1.php'),
//   array('viewlets',       '/^Groot.*Viewlet$/', '/^Groot(.*)Viewlet$/', 'viewlet.$1.php'),
//   array('views',          '/^Groot.*View$/',    '/^Groot(.*)View$/',    'view.$1.php')
//   )));
?>