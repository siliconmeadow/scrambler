<?php
/**
 * @file
 * API class for the scramble module.
 */

namespace Drupal\scrambler\BLL;

use Drupal\scrambler\Controller;

class ImplementationObject {
  public $module;
  public $table;
  public $fields;
  public $method;

  /**
   * Execute the scramble method for the given fields.
   */
  public function execute() {
    if (!$this->method) {
      return FALSE;
    }
    // @todo: Apply method for field values.
    // $test_value = 'abcdefghijklmnopqrstuvwxyz';
    // $function = $this->method;
    // $function($test_value);
    // @todo: Temporary printing for Drush. Should be removed and introduced in a logging functionality.
    print "Group $this->group scramble on $this->base_table (method: $this->method).\n";
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
   * @param array $implementation
   *   Contains the implementation data structure array.
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
    $object = new ImplementationObject();
    // Name of the group.
    $object->module = key($group);
    $params = array_shift($group);
    // Name of the table that contains the fields.
    $object->table = $params['base_table'];
    // An array containing the fields.
    $object->fields = $params['fields'];
    // A string that represents the scramble method.
    $object->method = $this->getMethodName($object->group, $params);
    // Execute the method.
    $object->execute();
    // Free up memory.
    unset($object);
  }

  /**
   * Get and validate the method name if exists.
   *
   * @param string $module
   *   The current module we are trying to get the method name from.
   * @param array $params
   *   All the parameters given by the scrambler_hook.
   *
   * @return string|bool
   *   Returns the method name or FALSE if the method name does not exist.
   */
  private function getMethodName($module, $params) {
    // First check if the group method exists.
    $method = '_' . $module . '_method_' . $params['method'];

    if (function_exists($method)) {
      return $method;
    }

    // Second check if the method exists in the scrambler api.
    $method = '_scrambler_method_' . $params['method'];

    if (function_exists($method)) {
      return $method;
    }

    // In case no method name was found, register it in watchdog.
    watchdog(
      'scrambler',
      'Non-existing function %m(&$data) {}.',
      array('%m' => $method),
      WATCHDOG_ERROR
    );

    return FALSE;
  }
}
