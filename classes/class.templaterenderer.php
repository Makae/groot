<?php
  /**
   * The TemplateRenderer allows the decoupoling of php from html
   *
   * The TemplateRenderer uses the singleton pattern
   *
   * @author: M. Käser
   * @date:   25.10.2014
   */
  class TemplateRenderer {
    private static $instance = null;
    private static $var_prefix = '{%';
    private static $var_suffix = '%}';
    private static $tmp_dir = 'TR_TEMP';
    private static $salt = TEMPLATE_SALT;

    private function __construct() {}

    /**
     * Singleton call for creating the instance
     * Creates a new instance if it does not already exist
     *
     * @return Core $instance - The instance
     */
    public function instance() {
      if(self::$instance != null)
        return self::$instance;
      return self::$instance = new TemplateRenderer();
    }

    /**
     * Sets the template directory, please check
     * that the permissions are set correctly
     * @param string $dir - the template directory which contains the templates
     */
    public function setTplDir($dir) {
      $dir = realpath($dir);
      if(!file_exists($dir) || !is_dir($dir))
        throw new Exception("This folder $dir does not exist!");

      TemplateRenderer::$tmp_dir = $dir;
    }

    /**
     * Renders a template file
     * @params string $template - path of template file
     * @params assoc-array $args - associative array with the to be replaced values
     */
    public function render($template, $args) {
      if(!file_exists($template))
        throw new Exception('The template "' . $template . '" does not exist');

      $html = Utilities::getFileContent($template);
      $html = Utilities::templateReplace($html, $args, static::$var_prefix, static::$var_suffix);
      return $html;
    }

    /**
     * Renders a template with additional control keywords like for / ifs and assoc-arrays
     *
     * @param $template - path of the template
     * @param $args -> associative array with the values
     */
    public function extendedRender($template, $args) {
      $html = Utilities::getFileContent($template);

      $tpl_php = $this->_prepareVariables($html, $args);
      $tpl_php = $this->_prepareFors($tpl_php);
      $tpl_php = $this->_prepareIfs($tpl_php);

      return $this->_templateInclude($template, $tpl_php, $args);
    }

    /**
     * Generates a temporary-php File which is then included,
     * the printed values are then returned
     * @param string $template - template file
     * @param string $php - PHP / html code to be written in template file
     * @param assoc-array $args - associative array with the arguments, they are extracted and used in the php-code
     */
    private function _templateInclude($template, $php, $args) {
      $tmp_path = static::$tmp_dir . '/tmp.' . basename($template). '.' . Utilities::hash($template, static::$salt) . '.php';

      $fh = fopen($tmp_path, 'w');
      fwrite($fh, $php);

      extract($args);
      ob_start();
      require($tmp_path);
      $html = ob_get_clean();
      return $html;
    }

    /**
     * Replaces templates variables {$value} such that they are echoed when executed
     * Additionally replaces {$value.key} to $value['key'];
     *
     * @param string $html - the html string with the variable markers
     *
     */
    private function _prepareVariables($html) {
      $html = preg_replace('/\{(\$[^\}]*)\}/mi', '<?php if(isset($1)) echo $1;?>', $html);
      $html = preg_replace('/(\$[a-zA-Z0-9_]+)(\.)([a-zA-Z0-9_]+)/mi', '$1[\'$3\']', $html);
      return $html;
    }

    /**
     * Replaces {if condition="[COND]"}[IN_IF]{/if} to <?php if([COND]) { [IN_IF]} ?> in order to be properly executed
     *
     * @param string $html - the html string with the markers
     */
    private function _prepareIfs($html) {
      $matches = array();
      preg_match_all('/\{\/?if[^\}]*}/mi', $html, $matches, PREG_OFFSET_CAPTURE);

      if(count($matches[0]) % 2 != 0)
        throw new Exception("Template $template has no matching if tags count");

      $matches = $matches[0];
      foreach($matches as $match) {
        $match_str = $match[0];
        $match_len = $match[1];
        // is {if}
        if(!preg_match('/\{\/if[^\}]*/i', $match_str)) {
          $html = $this->_replaceIfOpen($html, $match_str);
        // is {/if}
        } else {
          $html = $this->_replaceIfClose($html, $match_str);
        }
      }
      return $html;
    }

    /**
     * Replaces for markers: {for array="$entries"}[IN_FOR]{/for} to
     * <?php foreach($entries as $key => $value){ [IN_FOR] } ?> in order to
     * be properly executed when the html is included
     *
     * @param string $html - the html string with the markers
     */
    private function _prepareFors($html) {
      $matches = array();
      preg_match_all('/\{\/?for[^\}]*}/mi', $html, $matches, PREG_OFFSET_CAPTURE);

      if(count($matches[0]) % 2 != 0)
        throw new Exception("Template $template has no matching for count");

      $depth = 0;
      $matches = $matches[0];

      foreach($matches as $match) {
        $match_str = $match[0];
        // is {for}
        if(!preg_match('/\{\/for[^\}]*/i', $match_str)) {
          $depth++;
          $html = $this->_replaceForOpen($html, $match_str, $depth);
        // is {/for}
        } else {
          $html = $this->_replaceForClose($html, $match_str, $depth);
          $depth--;
        }
      }
      return $html;
    }

    /**
     * Replaces the first opening "for" with the suitable php-code
     *
     * @param string $html - html with the markers
     * @param string $tmpl_for - the template content of the "for"
     * @param int $depth - is used to distinguish the depth of nested loops
     */
    private function _replaceForOpen($html, $tmpl_for, $depth) {
      $matches = array();
      preg_match('/array=\"\$?([^"]*)\"/i', $tmpl_for, $matches);
      $array = $matches[1];
      $array = str_ireplace('.', '->', $array);
      $php_for = '<?php foreach($' . $array . ' as $key => $value) {' .
                 '  $_key' . $depth . ' = $key;' .
                 '  $_value' . $depth . ' = $value;' .
                 '?>';
      $num = 1;
      $html = str_replace($tmpl_for, $php_for, $html, $num);

      return $html;
    }

    /**
     * Replaces the first closing "for" with the suitable php-code
     *
     * @param string $html - html with the markers
     * @param string $tmpl_for - the template content of the "for"
     * @param int $depth - is used to distinguish the depth of nested loops
     */
    private function _replaceForClose($html, $tmpl_for, $depth) {
      $php_for = '<?php } ' .
                 '  if(isset($_key)) { $key = $_key' . $depth . ';' .
                 '  unset($_key' . $depth . ');}' .
                 '  if(isset($_value)) { $value = $_value' . $depth . ';' .
                 '  unset($_value' . $depth . ');}' .
                 '?>';
      $num = 1;
      $html = str_replace($tmpl_for, $php_for, $html, $num);

      return $html;
    }

    /**
     * Replaces the first opening "if" with the suitable php-code
     *
     * @param string $html - html with the markers
     * @param string $tmpl_if - the template content of the "if"
     */
    private function _replaceIfOpen($html, $tmpl_if) {
      $matches = array();
      preg_match('/condition=\"(\$?[^"]*)\"/i', $tmpl_if, $matches);
      $condition = $matches[1];
      $php_if = '<?php if(' . $condition . ') { ?>';
      $num = 1;
      $html = str_replace($tmpl_if, $php_if, $html, $num);

      return $html;
    }

    /**
     * Replaces the closing opening "if" with the suitable php-code
     *
     * @param string $html - html with the markers
     * @param string $tmpl_if - the template content of the "if"
     */
    private function _replaceIfClose($html, $tmpl_if) {
      $php_if = '<?php } ?>';
      $num = 1;
      $html = str_replace($tmpl_if, $php_if, $html, $num);

      return $html;
    }

  }
?>