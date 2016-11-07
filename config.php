<?php
  $root_dir = dirname(__FILE__);
  $root_url = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
  $folders = explode('/', $_SERVER['PHP_SELF']);
  $groot_folder = $folders[1];

  define('ROOT', $root_dir);
  define('WWWROOT', $root_url . '/' . $groot_folder . '/');
  define('WWWTHEME', WWWROOT . '/theme');
  define('WWWCSS', WWWTHEME . '/css');
  define('WWWJS', WWWTHEME . '/js');

  define('SQL_DEBUGGING', false);
  define('USER_SALT', '!$9*+P/l_o');

  define('DEFAULT_LANGUAGE', 'de');
  define('AVAILABLE_LANGUAGES', 'de,en');

  define('DB_HOST', 'localhost');
  define('DB_USER', 'groot');
  define('DB_PASSWORD', 'groot');
  define('DB_DATABASE', 'groot');

  define('TEMPLATE_TMP_DIR', $root_dir . '/theme/templates/tmp/');
  define('TEMPLATE_SALT', 'b7!$.L£4t_');

  define('VIEWLETS', 'footer,header,navi');
  /*
   * @desc: Define the folders and class patterns. This is sent to the autoloader which decides which file to
   *        include
   * @param1: Path of the folder from the root directory
   * @param2: Regex which checks if the called class matches the pattern
   * @param3: Search-Regex for the file-string
   * @param4: Replace-Pattern for the file-string
   */
  define('AUTOLOAD_FOLDERS', serialize(array(
    array('interfaces',     '/I[A-Z][a-z]+/',     '/^I([A-Z][a-z]+)$/',   'interface.$1.php'),
    array('classes',        '/.*/',               '/^(.*)$/',             'class.$1.php'),
    array('classes/models', '/^.*Model$/',        '/^(.*)Model$/',        'class.$1.php'),
    array('classes/joins', '/^.*Join$/',        '/^(.*)Join$/',        'class.$1.php'),
    array('viewlets',       '/^Groot.*Viewlet$/', '/^Groot(.*)Viewlet$/', 'viewlet.$1.php'),
    array('views',          '/^Groot.*View$/',    '/^Groot(.*)View$/',    'view.$1.php')
    )));
?>