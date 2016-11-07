<?php
    // EXAMPLE MODEL BookCategory
  class BookcategoryModel extends BaseModel {
    protected static $TABLE = 'bookcategory';
    protected static $COLUMN_NAMES = array('book_id', 'category_key');
    protected static $COLUMN_TYPES = array('INT(10) NOT NULL', 'VARCHAR(50) NOT NULL');

    public function lang() {
      return $this->data['lang'];
    }

  }
?>