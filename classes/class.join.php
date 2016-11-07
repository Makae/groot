<?php
  /**
   * This class provides a interface for joining
   * two table together
   *
   * @state experimental
   */
  abstract class Join {
    const JOIN_LEFT = 'LEFT';
    const JOIN_RIGHT = 'LEFT';
    const JOIN_OUTER = 'OUTER';
    const JOIN_INNER = 'INNER';
    const JOIN_LINNER = 'LEFT INNER';
    const JOIN_RINNER = 'RIGHT INNER';
    const DEFAULT_FOREIGN_ID = 'id';

    // JoinRelation::JOIN_LEFT
    protected static $JOIN_TYPE;
    // array('Model', 'table_short_name', 'column_join')
    protected static $CONFIG_LEFT;
    // array('Model2', 'table_short_nam2e', 'column_join2')
    protected static $CONFIG_RIGHT;
    // array('fk_model1', 'fk_model2');
    protected static $JOIN_RENAMING_ID;

    public function find($conditions=null) {
      list($left, $left_model, $left_columns, $left_id) = static::_joinConfigIndexed(static::$CONFIG_LEFT, 'left');
      list($right, $right_model, $right_columns, $right_id) = static::_joinConfigIndexed(static::$CONFIG_RIGHT, 'right');
      $col_ids = array($left_id, $right_id);
      $rows = DB::instance()->join($left, $right, static::$JOIN_TYPE, $conditions, $col_ids);


      if(count($rows) <= 0)
        return array();

      $values = array();
      foreach($rows as $row) {
        if($row['join_left_id'] == null)
          $left_obj = null;
        else
          $left_obj = new $left_model($row['join_left_id']);

        if($row['join_right_id'] == null)
          $right_obj = null;
        else
          $right_obj = new $right_model($row['join_right_id']);

        $values[] = array($left_obj, $right_obj);
      }
      echo "<pre>";
      die(var_dump($values));
      return $values;
    }

    public function findAssoc($conditions=null) {
      list($left, $left_model, $left_columns) = static::_joinConfigIndexed(static::$CONFIG_LEFT, 'left');
      list($right, $right_model, $right_columns) = static::_joinConfigIndexed(static::$CONFIG_RIGHT, 'right');
      $cols = array_merge($left_columns, $right_columns);

      return DB::instance()->join($left, $right, static::$JOIN_TYPE, $conditions, $cols);
    }

    private static function _joinConfigIndexed($array, $position='left') {
      return array_values(static::_joinConfig($array, $position));
    }

    private static function _joinConfig($array, $position='left') {
      $model = ucfirst(array_shift($array)) . 'Model';
      array_unshift($array, $model::table());

      $cols[] = $array[1] . '.id AS join_' . $position . '_id';

      foreach($model::column_names() as $col)
        $cols[] = $array[1] . '.' . $col;

      return array('params' => $array,
                   'model' => $model,
                   'columns' => $cols,
                   'col_id' => $cols[0]);
    }

  }
?>