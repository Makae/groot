<?php
  class GrootAjaxView implements IView {

    public function name() {
      return 'ajax';
    }

    public function viewletMainMenu() {
      return null;
    }

    public function viewletNavi() {
      return array();
    }

    public function viewletFooter() {
      return null;
    }

    public function process() {
      // Here comes the processing of the field-parameters
    }

    public function render() {
      return "This view is only called via ajax.";
    }

    public function ajaxWiki() {
      $query = isset($_REQUEST['query']) ? $_REQUEST['query'] : null;
      $result = Utilities::wiki($query);
      if(!$result)
        $result = i('We could not find a suitable wikipedia article');
      // we will return the value as json encoded content
      return json_encode(array('query' => $query, 'wiki' => $result));
    }

  }
?>