<?php
/**
* class to define the user DB Table
*/
  class UserModel extends BaseModel {
    protected static $TABLE = 'user';
    protected static $COLUMN_NAMES = array('user_name',
                                           'first_name',
                                           'last_name',
                                           'password',
                                           'lang',
                                           'is_admin',
                                           'streetname',
                                           'streetnumber',
                                           'zip',
                                           'city',
                                           'state',
                                           'email');

    protected static $COLUMN_TYPES = array('VARCHAR(20) UNIQUE NOT NULL',
                                           'VARCHAR(50) NOT NULL',
                                           'VARCHAR(50) NOT NULL',
                                           'VARCHAR(32) NOT NULL',
                                           'VARCHAR(2) NOT NULL',
                                           'BOOLEAN DEFAULT false',
                                           'VARCHAR(100) DEFAULT NULL',
                                           'VARCHAR(20) DEFAULT NULL',
                                           'INT(16) DEFAULT NULL',
                                           'VARCHAR(100) DEFAULT NULL',
                                           'VARCHAR(100) DEFAULT NULL',
                                           'VARCHAR(100) DEFAULT NULL');

    public function lang() {
      return $this->data['lang'];
    }

  }
?>