<?php
/**
 * @file
 * API class for the scramble module.
 */

namespace Drupal\scrambler\BLL;

use Drupal\scrambler\Config\Variable;
use Drupal\scrambler\Controller;

/**
 * API class object.
 */
class API {

  /**
   * @var \Drupal\scrambler\Config\Variable
   */
  private $variable;

  /**
   * API class constructor.
   */
  public function __construct() {
    $this->variable = new Variable();
  }

  /**
   * Start scrambling the database.
   */
  public function scramble() {
    $parameters = new Controller\ScrambleController();

    // @todo: Check for implementations here with $parameters->getImplementations()

    // TODO: Instead of shuffling, swap step-by-step and check for other DIFFERENT values.
    if ($this->variable->getTitle() == 1) {
      foreach ($this->getFieldEntities('title') as $entity_type => $entities) {
        $ids = $shuffled = array_keys($entities);
        shuffle($shuffled);
        foreach ($ids as $key => $id) {
          $this->swapFields($entity_type, $id, $shuffled, 'title');
        }
      }
    }
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
  }

  /**
   * Get a simple fields array.
   *
   * @return array
   *   Returns an array of fields.
   */
  public function getScramblerFields($all = TRUE) {
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
    $this->swapValues($entity_one, $entity_two, $field_name);
    $this->removeFieldValue($entity_one, $field_name);
    entity_save($entity_type, $entity_one);
    entity_save($entity_type, $entity_two);
  }

  /**
   * Swam the values for the given entities and field name.
   *
   * @param \Entity $entity_one
   *   Contains the first entity.
   * @param \Entity $entity_two
   *   Contains the second entity.
   * @param string $field_name
   *   Contains the field name of which the value needs to be swapped.
   */
  private function swapValues(&$entity_one, &$entity_two, $field_name) {
    $field_one = $entity_one->{$field_name};
    $entity_one->{$field_name} = $entity_two->{$field_name};
    $entity_two->{$field_name} = $field_one;
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

  private function removeFieldValue($entity, $field_name) {

  }
}
