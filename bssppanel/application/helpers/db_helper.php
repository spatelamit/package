<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('my_update_batch')) {

    // Batch Update With Multiple Keys
    function my_update_batch($db, $table = '', $set = NULL, $index = NULL, $index_update_key = '') {
        if ($table === '' || is_null($set) || is_null($index) || !is_array($set)) {
            return FALSE;
        }

        $sql = 'UPDATE ' . $db->protect_identifiers($table) . ' SET ';
        $ids = $when = array();
        $cases = '';

        //generate the WHEN statements from the set array
        foreach ($set as $key => $val) {
            $ids[] = $val[$index];
            foreach (array_keys($val) as $field) {
                if ($field != $index && $field != $index_update_key) {
                    $when[$field][] = 'WHEN ' . $db->protect_identifiers($index)
                            . ' = ' . $db->escape($val[$index]) . ' THEN ' . $db->escape($val[$field]);
                } elseif ($field == $index) {
                    //if index should also be updated use the new value specified by index_update_key
                    $when[$field][] = 'WHEN ' . $db->protect_identifiers($index)
                            . ' = ' . $db->escape($val[$index]) . ' THEN ' . $db->escape($val[$index_update_key]);
                }
            }
        }

        //generate the case statements with the keys and values from the when array
        foreach ($when as $k => $v) {
            $cases .= "\n" . $db->protect_identifiers($k) . ' = CASE ' . "\n";
            foreach ($v as $row) {
                $cases .= $row . "\n";
            }
            $cases .= 'ELSE ' . $k . ' END, ';
        }
        $sql .= substr($cases, 0, -2) . "\n"; //remove the comma of the last case
        $sql .= ' WHERE ' . $index . ' IN (' . implode(',', $ids) . ')';
        return $sql;
        //return $db->query($sql);
    }

}

/*
  if (!function_exists('update_batch1')) {

  function update_batch1($table = '', $set = NULL, $index = NULL, $index1 = NULL) {
  // Combine any cached components with the current statements
  $this->_merge_cache();

  if (is_null($index)) {
  if ($this->db_debug) {
  return $this->display_error('db_must_use_index');
  }
  return FALSE;
  }

  if (is_null($index1)) {
  if ($this->db_debug) {
  return $this->display_error('db_must_use_index');
  }
  return FALSE;
  }

  if (!is_null($set)) {
  $this->set_update_batch($set, $index);
  }

  if (count($this->ar_set) == 0) {
  if ($this->db_debug) {
  return $this->display_error('db_must_use_set');
  }

  return FALSE;
  }

  if ($table == '') {
  if (!isset($this->ar_from[0])) {
  if ($this->db_debug) {
  return $this->display_error('db_must_set_table');
  }
  return FALSE;
  }

  $table = $this->ar_from[0];
  }

  // Batch this baby
  for ($i = 0, $total = count($this->ar_set); $i < $total; $i = $i + 100) {
  $sql = $this->_update_batch($this->_protect_identifiers($table, TRUE, NULL, FALSE), array_slice($this->ar_set, $i, 100), $this->_protect_identifiers($index), $this->ar_where);

  $this->query($sql);
  }

  $this->_reset_write();
  }

  }
 */
?>