<?php
/**
 * @file
 * API class for the scramble module.
 */

namespace Drupal\scrambler;

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
    watchdog('scrambler',
      "Executing method %me for module %mo on table %t.",
      array(
        '%me' => $this->method,
        '%mo' => $this->module,
        '%t' => $this->table
      ),
      WATCHDOG_INFO
    );

    return TRUE;
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
    foreach($implementation as $module => $groups) {
      $this->scrambleImplementationGroup($module, $groups);
    }
  }

  /**
   * Scramble the implementation group.
   *
   * @param array $groups
   *   Contains the implementation groups structure array.
   */
  private function scrambleImplementationGroup($module, $groups) {
    foreach($groups as $group) {
      $object = new ImplementationObject();
      $params = $group;
      // Name of the module
      $object->module = $module;
      // Name of the table that contains the fields.
      $object->table = $params['base_table'];
      // An array containing the fields.
      $object->fields = $params['fields'];
      // A string that represents the scramble method.
      $object->method = $this->getMethodName($object->module, $params);
      // Execute the method.
      if ($object->execute()) {
        watchdog(
          'scrambler',
          'Successfull execution of method %m.',
          array('%m' => $object->method),
          WATCHDOG_INFO
        );
      }
      else {
        watchdog(
          'scrambler',
          'Error while executing function %m.',
          array('%m' => $method),
          WATCHDOG_ERROR
        );
      }
      // Free up memory.
      unset($object);
    }
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

    // First check if the group method exists.
    $method = '_' . $module . '_method_' . $params['method'];
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
