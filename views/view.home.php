<?php
  //generates the main content of the home site
  class GrootHomeView implements IView {

    public function name() {
      return 'home';
    }

    public function viewletMainMenu() {
      return null;
    }

    public function viewletNavi() {
      return null;
    }

    public function viewletFooter() {
      return null;
    }

    public function process() {
      // Here comes the processing of the field-parameters
    }

    public function render() {
      // Here comes the rendering process
    $htmlContent = "";//Main Content

    return $htmlContent;
}

  public function ajaxCall() {
    // we will return the value as json encoded content
    return json_encode('asdf');
  }
}
?>