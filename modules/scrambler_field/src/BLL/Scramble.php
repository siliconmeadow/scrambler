<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


namespace Drupal\scrambler_field\BLL;

use Drupal\scrambler_field\Config\Variable;

/**
 * Description of Scramble class
 */
class Scramble {
  private $api;

  /**
   * @var \Drupal\scrambler\Config\Variable
   */
  private $variable;

  /**
   * API class constructor.
   */
  public function __construct(&$api) {
    $this->api = $api;
    $this->variable = new Variable();
  }
  
  public function execute() {
    if ($this->variable->getTitle() == 1) {
      foreach ($this->getFieldEntities('title') as $entity_type => $entities) {
        $ids = $shuffled = array_keys($entities);
        shuffle($shuffled);
        foreach ($ids as $key => $id) {
          $this->swapFields($entity_type, $id, $shuffled, 'title');
        }
      }
    }
    // @todo: Instead of shuffling, swap step-by-step and check for other DIFFERENT values.
    foreach ($this->getScramblerFields() as $field_name) {
      $field = field_info_field($field_name);
      if (field_has_data($field) && $this->fieldStoredInSql($field)) {
        $field_entities = $this->getFieldEntities($field_name);
        foreach ($field_entities as $entity_type => $entities) {
          $ids = $shuffled = array_keys($entities);
          shuffle($shuffled);
          foreach ($ids as $key => $id) {
            $this->swapFields($entity_type, $id, $shuffled, $field_name);
          }
        }
      }
    }
    // @todo: We are expecting the scramble to always return true, for now.
    return TRUE;
  }

  /**
   * Get the field entities by field name.
   *
   * @param string $field_name
   *   Contains the field name.
   *
   * @return array
   *   Returns an array of entities.s
   */
  private function getFieldEntities($field) {
    $query = new \EntityFieldQuery();
    if ($field != 'title') {
// TODO Check reason for non working fieldcondition query
//      $query->fieldCondition($field);
    }
    $query->entityCondition('entity_type', 'node');
    $query->entityCondition('bundle', array_keys(array_filter($this->variable->getContentTypes())), 'IN');

    return $query->execute();
  }

  /**
   * Get a simple fields array.
   *
   * @return array
   *   Returns an array of fields.
   */
  private function getScramblerFields($all = TRUE) {
    $entities = entity_load('scrambler_field');
    $fields = field_info_fields();
    $result = array();
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
  private function fieldStoredInSql($field) {
    return array_key_exists('storage', $field) &&
        array_key_exists('type', $field['storage']) &&
        ($field['storage']['type'] == 'field_sql_storage');
  }
  
  /**
   * Load entities by id and swap field values by given field name.
   *
   * @param string $entity_type
   *   Contains the current entity type.
   * @param int $id_one
   *   Contains the id of the first entity.
   * @param array $shuffled
   *   Contains an array of shuffled ids.
   * @param string $field_name
   *   Contains the field name.
   */
  private function swapFields($entity_type, $id_one, $shuffled, $field_name) {
    $shuffle_key = 0;
    $doContinue = TRUE;
    $entity_one = entity_load_single($entity_type, $id_one);
    $entity_two = entity_load_single($entity_type, $shuffled[$shuffle_key]);
    while ($doContinue && ($entity_one->{$field_name} == $entity_two->{$field_name})) {
      $shuffle_key += 1;
      $doContinue = isset($shuffled[$shuffle_key]);
      if ($doContinue) {
        $entity_two = entity_load_single($entity_type, $shuffled[$shuffle_key]);
      }
    }
    if ($entity_one->{$field_name} == $entity_two->{$field_name}) {
      // TODO - Create next scrambling method in case of exact same value.
    }
    unset($shuffle_key[$shuffle_key]);
    $this->api->swapValues($entity_one, $entity_two, $field_name);
    $this->removeFieldValue($entity_one, $field_name);
    entity_save($entity_type, $entity_one);
    entity_save($entity_type, $entity_two);
  }  

  /**
   * Get all content types.
   *
   * @return array
   *   Returns a simple array of all content types.
   */
  private function getContentTypes() {
    $types = node_type_get_types();
    $options = array();
    foreach ($types as $key => $type) {
      $options[$key] = $type->name;
    }
    return $options;
  }

  private function removeFieldValue($entity, $field_name) {

  }
  
}
