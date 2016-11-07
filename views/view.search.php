<?php
  class GrootSearchView implements IView {

    public function name() {
      return 'search';
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

    public function ajaxAutocomplete() {
      $query = isset($_REQUEST['query']) ? $_REQUEST['query'] : null;
      $cat = isset($_REQUEST['cat']) ? $_REQUEST['cat'] : null;
      $num = isset($_REQUEST['num']) ? $_REQUEST['num'] : 10;

      $result = $this->_getSearchResult($query, $cat, 0, 10, true);
      return json_encode($result);

    }

    public function process() {
      // Here comes the processing of the field-parameters
    }

    private function _getSearchResult($str, $category, $page=0, $size=10, $only_titles=false) {
      $category = !isset($category) || $category == null || $category == '' ? null : $category;
      $books = Core::instance()->getDb()->searchBooks($str, $category, $page, $size, null, $only_titles);
      if($str != '') {
        foreach($books as $key => $book) {
          $books[$key]['title'] = Utilities::highlight($book['title'], $str);
          $books[$key]['author'] = trim($books[$key]['author']);
          $description = Utilities::cutText($book['description'], 300);
          $books[$key]['description'] = Utilities::highlight($description, $str);
        }
      }
      $total = Core::instance()->getDb()->countSearchBooks($str, $category);
      $result = array('books' => $books, 'total' => $total);
      return $result;
    }

    public function render() {
      $page_size = 10;
      $query = isset($_REQUEST['query']) ? $_REQUEST['query'] : null;
      $cat = isset($_REQUEST['cat']) ? $_REQUEST['cat'] : null;
      $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 0;
      $result = $this->_getSearchResult($query, $cat, $page, $page_size);

      $args = array(
        'books' => $result['books'],
        'text_details' => i('To the details')
      );
      $content = TemplateRenderer::instance()->extendedRender('theme/templates/snippets/booklist.html', $args);

      $pagination = Utilities::pagination($page, $result['total'], $page_size, '?view=search&query='. $query . '&cat' . $cat . '&page={page}');
      $args = array(
        'title' => i('We found following books for you'),
        'result' => $content,
        'pagination' => $pagination
        );
      return TemplateRenderer::instance()->extendedRender('theme/templates/views/search.html', $args);
    }

    public function ajaxCall() {
      // we will return the value as json encoded content
      return json_encode('asdf');
    }

  }
?>