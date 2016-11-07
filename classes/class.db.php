<?php
  /**
   * This class provides a simple interface for interacting with
   * the MySQL-Database
   *
   * The DB uses the singleton pattern
   *
   * @author: M. Käser
   * @date: 05.10.2014
   *
   */
  class DB {
    protected static $instance = null;

    protected $host = null;
    protected $user = null;
    protected $pwd = null;
    protected $db = null;
    // Prevents executing the create table method if the table was already created
    protected $table_cache = array();
    protected $mysqli = null;

    /**
     * Creates an utf8-connection to the database
     *
     * @param string $host
     * @param string $user
     * @param string $pwd
     * @param string $db
     */
    protected function __construct($host=null, $user=null, $pwd=null, $db=null) {
      $this->host = is_null($host) ? DB_HOST : $host;
      $this->user = is_null($user) ? DB_USER : $user;
      $this->pwd = is_null($pwd) ? DB_PASSWORD : $pwd;
      $this->db = is_null($db) ? DB_DATABASE : $db;

      // this is the conneciton to the MySQL-DB
      $this->mysqli = new mysqli($this->host, $this->user, $this->pwd, $this->db);
      if (mysqli_connect_errno()) {
        die(mysqli_connect_error());
      }
      //mysqli_set_charset('utf8', $this->mysqli);
    }

    /**
     * Closes the DB-Connection
     */
    public function __destruct() {
      $this->mysqli->close();
    }

    /**
     * Singleton call for creating the instance
     * Creates a new instance if it does not already exist
     *
     * It differentiates, if the called class is inherited
     *
     * @return DB $instance - The instance
     */
    public static function instance($host=null, $user=null, $pwd=null, $db=null) {
      if(self::$instance != null)
        return self::$instance;

      $cls = get_called_class();
      return self::$instance = new $cls($host, $user, $pwd, $db);
    }

    /**
     * Sugar for calling mysqli_query
     */
    public function query($sql) {
      if(SQL_DEBUGGING === true) {
        echo "<pre>";
        var_dump($sql);
        echo "</pre>";
      }
      $result = $this->mysqli->query($sql);
      return $result;
    }

    /**
     * checks if a table exists
     * @source: http://php.net/manual/en/function.mysql-tablename.php#41830
     */
    public function tableExists($table) {
      if(($result = $this->mysqli->query("SHOW TABLES LIKE '".$table."'")) && $result->num_rows == 1)
        return true;
      return false;
    }

    /**
     * Selects the content of a table by providing different parameters
     * which are then inserted in the SQL-Query
     *
     * @param mixed $table -  check _prepareEntitiesSql();
     * @param mixed $conditions - check _prepareConditionsSql();
     * @param mixed $columns - check _prepareEntitiesSql();
     * @param mixed $orders - check _prepareOrdersSql();
     * @param mixed $limit - check _prepareLimitSql();
    */
    public function select($table, $conditions=null, $columns=null, $orders=null, $limit=null) {
      $query = 'SELECT {%columns%} FROM {%table%} {%conditions%} {%orders%} {%limit%}';
      $args = $this->_prepareArgs($table, $conditions, $columns, $orders, $limit);

      $query = $this->_queryTemplate($query, $args);
      $result = $this->query($query);
      return $this->_assocRows($result);
    }

    /**
     * Joins two tables and returns relation
     *
     * @state experimental
     */
    public function join($left, $right, $type, $conditions=null, $join_columns=null, $orders=null, $limit=null) {
      $query = 'SELECT {%columns%} FROM {%table_left%} as {%name_left%} {%join%} {%table_right%} AS {%name_right%} ON ({%name_left%}.{%column_left%} = {%name_right%}.{%column_right%}) {%conditions%} {%orders%} {%limit%}';
      $args = $this->_prepareArgs(null, $conditions, $join_columns, $orders, $limit);
      $join_args = $this->_prepareJoinArgs($left, $right, $type);

      $args = array_merge($args, $join_args);
      $query = $this->_queryTemplate($query, $args);

      return $this->_assocRows($this->query($query));
    }


    /**
     * Sugar for returning the first entry inside the select()-response
     *
     * @return array<mixed> $entry - the first entry inside the found Relation
     */
    public function selectFirst($table, $conditions=null, $columns=null, $orders=null, $limit='0,1') {
      $data = $this->select($table, $conditions, $columns, $orders, $limit);
      if(count($data) > 0)
        return $data[0];
      return null;
    }

   /**
    * Updates the content of a Relation by provided values when
    * the provided conditions match
    *
    * @param mixed $table -  check _prepareEntitiesSql();
    * @param mixed $conditions - check _prepareConditionsSql();
    * @param mixed $columns - check _prepareEntitiesSql();
    */
    public function update($table, $values=null, $conditions=null) {
      $query = 'UPDATE {%table%} SET {%column_values%} {%conditions%}';
      $args = $this->_prepareArgs($table, $conditions);
      $args['column_values'] = $this->_prepareUpdateValues($values);

      $query = $this->_queryTemplate($query, $args);
      $result = $this->query($query);
      return $result;
    }

   /**
    * Deletes content of a table by provided values when
    * the provided conditions match
    *
    * @param mixed $table -  check _prepareEntitiesSql();
    * @param mixed $conditions - check _prepareConditionsSql();
    */
    public function delete($table, $conditions) {
      $query = 'DELETE FROM {%table%} {%conditions%}';
      $args = $this->_prepareArgs($table, $conditions);
      $query = $this->_queryTemplate($query, $args);
      $this->query($query);
    }

    /**
     * Truncates a table
     *
     * $param string $table - the name of the table which shall be truncated
     */
    public function truncate($table) {
      $query = 'TRUNCATE TABLE `' . $this->_esc($table) . '`';
      $this->query($query);
    }

    /**
     * Deletes a table
     *
     * $param string $table - the name of the table which shall be truncated
     */
    public function drop($table) {
      $query = 'DROP TABLE `' . $this->_esc($table) . '`';
      $this->query($query);
    }

    /**
     * Return a fetched mysql-resource as an associative array
     */
    protected function _assocRows($result) {
      $rows = array();

      while($row = $result->fetch_assoc())
        $rows[] = $row;

      return $rows;
    }

    /**
     * Sugar for single insert, returns the new id
     */
    public function insertSingle($table, $data, $columns=null) {
      $this->insert($table, $data, $columns);
      return $this->mysqli->insert_id;
    }

    /**
     * Insert method for sql entries.
     *
     * @param String $table - the table name
     *
     * @param Assoc-Array $data - Contains the column-names in the keys and the values in the values
     * @param Index-Array $data - Contains the values, it requires columns definitions in the $args array. Each row has to have the same number of elements as the columns array
     * @param Index-Array $rows - Contains the column-names. When tho $rows-Array is associative only the row-keys which matches the columns are saved.
    */
    public function insert($table, $data, $columns=null) {
      $query = 'INSERT INTO {%table%} ({%columns%}) VALUES {%values%}';
      if(Utilities::isAssoc($data) || !is_array($data[0]));
        $data = array($data);

      if(is_array($data))
        $data_is_assoc = Utilities::isAssoc($data[0]);

      if(!$data_is_assoc && !is_array($columns))
        throw new Exception("The provided values don't contain any column definitions");

      if(!$data_is_assoc && count($data[0]) != count($columns))
        throw new Exception("The provided column definition dont match the value definition");

      if($data_is_assoc)
        $columns = array_keys($data[0]);

      $sql_columns = $this->_prepareColumnSql($columns);
      $sql_values = null;

      foreach($data as $row) {
        $i = 0;
        $row = $this->_prepareValues($row);
        $row_values = array();

        foreach($columns as $key => $column) {
          if(!$data_is_assoc)
            $row_values[] = $row[$key];
          else if(array_key_exists($column, $row))
            $row_values[] = $row[$column];
        }

        $sql_values .= (!is_null($sql_values) ? "," : "\n\t") . '(' . implode(',', $row_values) . ')';
      }

      $query = $this->_queryTemplate($query, array(
        'table' => $this->_prepareTableSql($table),
        'columns' => $sql_columns,
        'values' => $sql_values
      ));

      $this->query($query);
    }

    /**
     * Creates a new Table in the DB. IF no column is an ID Column is automatically generated
     *
     * @param string $table - the name of the Table
     * @param array<ColumnDefinition> $columns : Array with ColumnDefinitions, check _prepareColumnDefinition
     * @param boolean $add_id_column - Do automatically add an column with the name ID
     * @param string $primary_column - Name of the column which shall be set as the primary key
    */
    public function createTable($table, $columns=null, $add_id_column=true, $primary_column=false) {
      if(in_array($table, $this->table_cache))
        return;

      $template = 'CREATE TABLE IF NOT EXISTS {%table%} ({%columns%} {%primary_key%}) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;' ;

      $columns = !is_array($columns) ? array() : $columns;
      if($add_id_column) {
        $primary_column = 'id';
        array_unshift($columns, array('id', 'INT(10) NOT NULL AUTO_INCREMENT'));
      }

      $column_sql = '';
      $num_cols = count($columns);
      for($i = 0; $i < $num_cols; $i++)
        $column_sql .= "\t" . $this->_prepareColumnDefinition($columns[$i]) . ($i+1 < $num_cols ? ",\n" : '');

      $query = $this->_queryTemplate($template, array(
        'table' => $this->_prepareTableSql($table),
        'columns' => "\n" . $column_sql,
        'primary_key' => $add_id_column || $primary_column ? ",\n\tPRIMARY KEY (" . $primary_column . ")\n" : "\n"
      ));

      $this->query($query);
      $this->table_cache[] = $table;
    }


    /**
     * Add collumns to a Table
     *
     * @param string $table - name of the table
     * @param array<ColumnDefinition> $columns : Array with ColumnDefinitions, check _prepareColumnDefinition
     */
    protected function addColumns($table, $columns) {
      $template = 'ALTER TABLE {%table%} ADD COLUMN {%colum%};';
      $query = 'START TRANSACTION;';
      foreach($columns as $col) {
        $query .= $this->_queryTemplate($template, array(
          'table' => $this->_esc($table),
          'column' => $this->_prepareColumnDefinition($col)
          ));
      }
      $query .= 'COMMIT;';

      $this->query($query);
    }

    /**
     * sugar for escaping mysql-String
     *
     * @param string $term - the term to be escaped
     */
    protected function _esc($term) {
      return $this->mysqli->escape_string($term);
    }

    /**
     * Sugar for calling the Uilities::templateReplace method
     * with the right parameters
     *
     * @param string $template - the string of the template with the placeholders
     * @param Assoc-Array $args - the values which shall be replaced inside the template
     */
    protected function _queryTemplate($template, $args) {
      return Utilities::templateReplace($template, $args, '{%', '%}');
    }

    /**
     * Sugar for preparing multiple arguments.
     *
     * @return Assoc-Array
     */
    protected function _prepareArgs($table=null, $conditions=null, $columns=null, $orders=null, $limit=null) {
      return array(
        'table' => $this->_prepareTableSql($table),
        'conditions' => $this->_prepareConditionsSql($conditions),
        'columns' => $this->_prepareColumnSql($columns, '*'),
        'orders' => $this->_prepareOrdersSql($orders),
        'limit' => $this->_prepareLimitSql($limit),
      );
    }

    /**
     * Sugar for preparing join-specific arguments.
     *
     *
     * @state experimental
     * @return Assoc-Array
     */
    protected function _prepareJoinArgs($left, $right, $type='LEFT') {
      return array(
        'table_left' => $this->_prepareEntitiesSql($left[0]),
        'name_left' => $this->_prepareEntitiesSql($left[1]),
        'column_left' => $this->_prepareEntitiesSql($left[2]),
        'table_right' => $this->_prepareEntitiesSql($right[0]),
        'name_right' => $this->_prepareEntitiesSql($right[1]),
        'column_right' => $this->_prepareEntitiesSql($right[2]),
        'join' => $type . ' JOIN'
      );
    }

    /**
     * Prepares an array of values for updatie-sql statement
     *
     * Escapes values and surounds the columnname with "`"
     *
     * @param array $values - As Exapmple: array('column' => 'value', 'column2' => 'value2');
     * @return string $sql - SQL section for update-values
     */
    protected function _prepareUpdateValues($values=null) {
      if(is_null($values))
        return '';

      $this->_prepareValues($values);

      if(!is_array($values))
        return '`' . $key . '` = ' . $this->_prepareValues($values);

      $sql = null;
      foreach($values as $key => $value) {
        if(!is_null($sql))
          $sql .= ' , ';

        $sql .= '`' . $key . '` = ' . $this->_prepareValues($value);
      }
      return $sql;
    }

    /**
     * Prepares a column definition
     * Is used for creating a table
     * Escapes values and surounds the columnname with "`"
     *
     * @param array $values - As Exapmple: array('amount', 'INT(10)');
     * @return string $sql - SQL section for update-values
     */
    protected function _prepareColumnDefinition($col=null) {
      if(is_null($col))
        return '';

      if(is_string($col))
        return '`' . $this->_esc($col) . '`';

      if(count($col) == 0)
        return '';

      if(count($col) == 1)
        return '`' . $this->_esc($col[0]) . '`';

      return '`' . $this->_esc($col[0]). '` ' . $this->_esc($col[1]);
    }

    /**
     * Generates the limit part of a select query
     *
     * @param string $type - limit section of query as string e.g. "0,1"
     * @param array<string> $single - array with only one entry with string e.g. "0,1"
     * @param array<int> $pair - array with pair of values e.g. array(0, 1);
     *
     * @return limit-section of a sql-query
     */
    protected function _prepareLimitSql($limit=null) {
      if(is_null($limit))
        return '';

      if(is_string($limit))
        return $this->_esc('LIMIT ' . $limit);

      if(count($limit) == 0)
        return '';

      if(count($limit) >= 2)
        return $this->_esc('LIMIT ' . $limit[0] . ', ' . $limit[1]);
      else
        return $this->_esc('LIMIT ' . $limit[0]);
    }

    /**
     * Generates the orders part of a select query
     *
     * @param string $orders - order as string. Returns "ORDER BY $orders"
     * @param array<string> $orders - multiple order parts. Returns "ORDER BY $orders[0],$orders[1],$orders[2],..."
     * @return string $sql - orders-section of a sql-query
     */
    protected function _prepareOrdersSql($orders=null) {
      if(is_null($orders))
        return '';

      if(is_string($orders))
        return $this->_esc('ORDER BY ' . $orders);

      $orders =  implode(', ', $orders);

      return $this->_esc('ORDER BY ' . $orders);
    }

    /**
     * Generates the conditions part of a query
     *
     * If an mixed array as conditions is provided, the conditions are concatenated by an "AND"
     * If an value inside this mixed array is defined as an array the values inside this array
     * are then concatenated by an "OR".
     * Example:
     *  @value string $value - adds ... " AND $key == $value"
     *  @value array $value - adds ... " AND ($key == $value OR $key == $value2) "
     *
     * @param string $conditions - returns "WHERE $conditions"
     * @param array<mixed> $conditions - E.g. {'key1'=>'value1', 'key2'=>array('value2_1', 'value2_2')}
     * @return The composed SQL-Conditions
     *
     */
    protected function _prepareConditionsSql($conditions=null) {
      if(is_null($conditions))
        return '';

      if(is_string($conditions))
        return 'WHERE ' . $this->_esc($conditions);

      if(!is_array($conditions))
        throw new Exception('The provided conditions are not valid');
      else if(count($conditions) == 0)
        return '';

      $cond = null;
      foreach($conditions as $key => $value) {
        $key = $this->_esc($key);
        $key = preg_replace('/\./', '`$0`', $key);
        if(!is_null($cond))
          $cond .= ' AND ';

        if(is_array($value))
          $cond .= '( ';

        // This generates or-conditions, such that `id`=1 AND ( `cat`='foo' OR `cat`='bar' )
        if(is_array($value)) {
          // encase each string value with String-Apostrophes (SQL-Requirement)
          $value = $this->_prepareValues($value);
          $cond .= '`' . $key . '` = ' . implode(' OR `' . $key . '` = ',  $value);
        } else {
          $cond .= '`' . $key . '` = ' . $this->_prepareValues($value);
        }

        if(is_array($value))
          $cond .= ' )';

      }

      return 'WHERE ' . $cond;
    }

    /**
     * Prepares a value of an SQL-Query
     * It encases it inside an apostroph if string,
     * otherwise not. Additionally it escapes the values
     *
     * @param mixed $values - values to be prepared
     * @return mixed $values - returns the prepared values
     */
    protected function _prepareValues($values) {
      if(!is_array($values)) {
        $val = $this->_esc($values);
        $val = is_string($val) ?  "'" . $val . "'" : $val;
        return $val;
      }

      foreach($values as &$val) {
        $val = $this->_esc($val);
        $val = is_string($val) ?  "'" . $val . "'" : $val;
      }
      return $values;
    }

    /**
     * Sugar for prepareing table-entities
     */
    protected function _prepareTableSql($value=null, $default='*') {
      return $this->_prepareEntitiesSql($value, $default);
    }

    /**
     * Sugar for prepareing column-entities
     */
    protected function _prepareColumnSql($value=null, $default=null) {
      return $this->_prepareEntitiesSql($value, $default);
    }

    /**
     * Concatenates table Entities
     *
     * It encases the entities with an "`"
     *
     * @param null $entities - allows for $default to be returned as cols statement
     * @param string $entities - the entity name
     * @param array<string> $entities - the entities as array
     */
    protected function _prepareEntitiesSql($entities=null, $default=null) {
      if(is_null($entities)) {
        if(!is_null($default))
          return $default;
        else
          throw new Exception('No Entity defined for SQL-Query');
      }

      if(!is_array($entities))
        $entities = array($entities);

      $result = '';
      $add = '';
      foreach($entities as $entity) {
        $entity = trim($entity);
        if(preg_match("/^([a-zA-Z][a-zA-Z0-9_-]*)(\.?)([a-zA-Z][a-zA-Z0-9_-]*)\s+([aA][sS]\s+)?([a-zA-Z][a-zA-Z0-9_-]*)$/", $entity)) {
          $add = preg_replace("/^([a-zA-Z][a-zA-Z0-9_-]*)(\.?)([a-zA-Z][a-zA-Z0-9_-]*)\s+([aA][sS]\s+)?([a-zA-Z][a-zA-Z0-9_-]*)$/", '`$1`$2`$3` $4 `$5`', $entity) . ' ';
        } else if(preg_match("/^([a-zA-Z][a-zA-Z0-9_-]*)\s+([aA][sS]\s+)?([a-zA-Z][a-zA-Z0-9_-]*)$/", $entity)) {
          $add = preg_replace("/^([a-zA-Z][a-zA-Z0-9_-]*)\s+([aA][sS]\s+)?([a-zA-Z][a-zA-Z0-9_-]*)$/", '`$1` $4 `$5`', $entity) . ' ';
        } else if(preg_match("/^([a-zA-Z][a-zA-Z0-9_-]*)(\.)([a-zA-Z][a-zA-Z0-9_-]*)$/", $entity)){
          $add = preg_replace("/^([a-zA-Z][a-zA-Z0-9_-]*)(\.)([a-zA-Z][a-zA-Z0-9_-]*)$/", '`$1`$2`$3`', $entity) . ' ';
        } else if(preg_match("/(.*\([^\)]+\))(\s+[Aa][Ss]\s+)([a-zA-Z0-9]+)/", $entity)) {
          $add = preg_replace("/(.*\([^\)]+\))(\s+[Aa][Ss]\s+)([a-zA-Z0-9]+)/", '$1$2`$3`', $entity) . ' ';
        } else {
          $add = '`' . $entity . '`';
        }
        $result .= $result == '' ? $add : ', ' . $add;
      }

      return $result;
    }

  }
?>