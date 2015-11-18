<?php
/**
 * @file
 * API class for the scramble module.
 */

namespace Drupal\scrambler\BLL;

use Drupal\scrambler\Controller;

class ImplementationObject {
  public $module;
  public $base_table;
  public $fields;
  public $method;

  /**
   * Execute the scramble method for the given fields.
   */
  public function execute() {
    // @todo: Apply method for field values.
    //$test_value = 'abcdefghijklmnopqrstuvwxyz';
    //$method($test_value);    
  }
}

/**
 * API class object.
 */
class API {
  /**
   * API class constructor.
   */
  public function __construct() {
    $this->parameters = new Controller\ScrambleController();
  }

  /**
   * Start scrambling the database.
   *
   * @return bool
   *   Returns only TRUE for now.
   */
  public function scramble() {
    foreach ($this->parameters->getImplementations() as $item) {
      $this->scrambleImplementation($item);
    }

    return TRUE;
  }

  /**
   * Start scrambling the database for one particular implementation.
   *
   * @param string $module_name
   *   Name of the module that is implementing this.
   * @param string $group_machine_name
   *   A group name defined by a user to group elements.
   * @param string $table_name
   *   Name of the table that contains the fields.
   * @param array $fields
   *   An array containing the fields.
   * @param string $method
   *   A string that represents the scramble method.
   * @param array $implementation
   *   An array with the structure for a particular implementation.
   */
  private function scrambleImplementation($implementation) {
    foreach($implementation as $group) {
      $this->scrambleImplementationGroup($group);
    }
  }

  /**
   * Scramble the implementation group.
   *
   * @param array $group
   *   Contains the implementation group structure array.
   */
  private function scrambleImplementationGroup($group) {
    $params = array_shift($group);
    $object = new ImplementationObject();
    $object->module = array_key_exists('module', $params) && !empty($params['module']) ? $params['module'] : 'scrambler';
    $object->base_table = $params['base_table'];
    $object->fields = $params['fields'];
    $object->method = '_' . $object->module . '_method_' . $params['method'];
    $object->execute();
    unset($object);
  }

  /**
   * Swap the values for the given entities and field name.
   *
   * @param \Entity $entity_one
   *   Contains the first entity.
   * @param \Entity $entity_two
   *   Contains the second entity.
   * @param string $field_name
   *   Contains the field name of which the value needs to be swapped.
   */
  public function swapValues(&$entity_one, &$entity_two, $field_name) {
    $field_one = $entity_one->{$field_name};
    $entity_one->{$field_name} = $entity_two->{$field_name};
    $entity_two->{$field_name} = $field_one;
  }
}
