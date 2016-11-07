<?php
    return;
    // EXAMPLE MODEL Book
    class BookModel extends BaseModel {
    protected static $TABLE = 'book';
    protected static $COLUMN_NAMES = array(
        'name',
        'isbn',
        'cover',
        'title',
        'author',
        'year_of_publication',
        'price',
        'currency',
        'available',
        'language',
        'description',
        'original_language',
        'number_of_pages',
        'version',
        'type',
        'genre'
        );
    protected static $COLUMN_TYPES = array(
        'VARCHAR(50) NOT NULL',
        'VARCHAR(32) NOT NULL',
        'VARCHAR(150) ',
        'VARCHAR(150) ',
        'VARCHAR(150) ',
        'INT(32) ',
        'FLOAT',
        'VARCHAR(32) ',
        'VARCHAR(32) ',
        'VARCHAR(60) ',
        'VARCHAR(5000) ',
        'VARCHAR(200) ',
        'VARCHAR(32) ',
        'INT(32) ',
        'FLOAT',
        'VARCHAR(60) ',
        'VARCHAR(60) '
        );

  }
?>