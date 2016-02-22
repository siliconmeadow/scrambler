<?php
/**
 * @file
 * Contains all Fields scramble functionalities.
 */

namespace Drupal\scrambler_field\BLL;


/**
 * Class Scramble.
 *
 * @package Drupal\scrambler_field\BLL
 */
class Scramble {
  /**
   * Get a simple fields array.
   *
   * @param bool $all
   *   Check if we need to get all fields.
   *
   * @return array
   *   Returns an array of fields.
   */
  public function getScramblerFields($all = TRUE) {
    $entities = entity_load('scrambler_field');
    $fields = field_info_fields();
    $result = array();

    if (!array_key_exists(-1, $entities)) {
      $result = array(-1 => 'title');
    }

    foreach ($fields as $key => $field) {
      if ($all || !array_key_exists($field['id'], $entities)) {
        $result[$field['id']] = $key;
      }
    }

    return $result;
  }

  /**
   * Check if field is stored in Sql.
   *
   * @param array $field
   *   Contains the field object.
   *
   * @return bool
   *   Returns TRUE if field is stored in sql.
   */
  private function fieldStoredInSql(array $field) {
    return array_key_exists('storage', $field) &&
      array_key_exists('type', $field['storage']) &&
      ($field['storage']['type'] == 'field_sql_storage');
  }

  /**
   * Get all content types.
   *
   * @return array
   *   Returns a simple array of all content types.
   */
  public function getContentTypes() {
    $types = node_type_get_types();
    $options = array();
    foreach ($types as $key => $type) {
      $options[$key] = $type->name;
    }
    return $options;
  }

  /**
   * Get the scramble data by field id.
   *
   * @param int $fid
   *   The field id.
   *
   * @return bool|array
   *   Returns an array with scramble data or FALSE on failure.
   */
  public function getScrambleFieldData($fid) {
    $field = field_info_field_by_id($fid);
    if (!isset($field) || !$this->fieldStoredInSql($field) || !field_has_data($field)) {
      return FALSE;
    }
    else {
      $data = array();
      $data['master_table'] = $this->getFieldTableName(
        $field, FIELD_LOAD_REVISION
      );
      $data['base_table'] = $this->getFieldTableName(
        $field, FIELD_LOAD_CURRENT
      );
      $data['field_name'] = $field['field_name'];
      $data['id'] = 'revision_id';
      $data['fields'] = array($field['storage']['details']['sql'][FIELD_LOAD_CURRENT][$data['base_table']]['value']);

      if (!isset($data['base_table'])) {
        return FALSE;
      }

      return $data;
    }
  }

  /**
   * Get the table name by field and type.
   *
   * @param array $field
   *   Expects the field entity object.
   * @param string $type
   *   Expects the FIELD_LOAD constant values.
   *
   * @return NULL|string
   *   Returns the table name, on failure returns NULL.
   */
  private function getFieldTableName(array $field, $type) {
    if (isset($field['storage']) &&
        isset($field['storage']['details']) &&
        isset($field['storage']['details']['sql']) &&
        isset($field['storage']['details']['sql'][$type]) &&
        is_array($field['storage']['details']['sql'][$type])) {
      $arr_table = array_keys($field['storage']['details']['sql'][$type]);
      return $arr_table[0];
    }

    return NULL;
  }

}
