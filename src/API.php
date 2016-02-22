<?php
/**
 * @file
 * API class for the scramble module.
 */

namespace Drupal\scrambler;

use Drupal\scrambler\Controller;

/**
 * Class ImplementationObject.
 *
 * @package Drupal\scrambler
 */
class ImplementationObject {

  public $module;
  public $table;
  public $masterTable = NULL;
  public $id;
  public $fields;
  public $method;

  /**
   * Execute the scramble method for the given fields.
   */
  public function execute() {
    if (!$this->method) {
      return FALSE;
    }

    $this->executeFieldScramble();

    watchdog(
      'scrambler',
      "Executing method %me for module %mo on table %t.",
      array(
        '%me' => $this->method,
        '%mo' => $this->module,
        '%t' => $this->table,
      ),
      WATCHDOG_INFO
    );

    return TRUE;
  }

  /**
   * Execute the fields scrambling method.
   */
  private function executeFieldScramble() {
    $up_records = $this->applyMethod($this->getTableRecords());
    foreach ($up_records as $record) {
      foreach ($this->fields as $field) {
        $fields[$field] = $record[$field];
      }

      $this->updateTable($this->table, $fields, $this->id, $record[$this->id]);

      if ($this->masterTable) {
        $this->updateTable(
          $this->masterTable, $fields, $this->id, $record[$this->id]
        );
      }
    }
  }

  /**
   * Get the table records.
   *
   * @return array
   *   Returns an array of records.
   */
  private function getTableRecords() {
    return db_select($this->table, 't')->fields('t')->execute();
  }

  /**
   * Apply the method on the records.
   *
   * @param array $records
   *   Contains all records.
   *
   * @return array
   *   Returns all records where the methods have been applied.
   */
  private function applyMethod(array $records) {
    $up_records = array();
    $function = $this->method;

    while ($record = $records->fetchAssoc()) {
      foreach ($this->fields as $field) {
        if (!empty($record[$field])) {
          $function($record[$field]);
          $up_records[] = $record;
        }
      }
    }

    return $up_records;
  }

  /**
   * Executes update table query.
   *
   * @param string $table
   *   Table name of the table to update.
   * @param array $fields
   *   Fields and values to update.
   * @param string $key
   *   Field name of the key id to apply conditions.
   * @param int $value
   *   Field value of the key id to apply conditions.
   */
  private function updateTable($table, array $fields, $key, $value) {
    db_update($table)
      ->fields($fields)
      ->condition($key, $value)
      ->execute();
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
   * @param string $haystack
   *   A machine name of a field to scramble on.
   *
   * @return bool
   *   Returns only TRUE for now.
   */
  public function scramble($haystack = NULL) {

    if ($haystack != NULL) {
      $this->scrambleImplementation($haystack);
    }
    else {
      foreach ($this->parameters->getImplementations() as $item) {
        $this->scrambleImplementation($item);
      }
    }

    drupal_flush_all_caches();

    return TRUE;
  }

  /**
   * Provide all groups in order to process them in batch.
   *
   * @return array
   *   Contains all groups with module and implementation.
   */
  public function scrambleBatchGroups() {
    $grouping = array();
    foreach ($this->parameters->getImplementations() as $item) {
      foreach ($item as $module => $groups) {
        foreach ($groups as $group) {
          $grouping[] = array($module => $group);
        }
      }
    }
    return $grouping;
  }

  /**
   * Start scrambling the database for one particular implementation.
   *
   * @param array $implementation
   *   Contains the implementation data structure array.
   */
  private function scrambleImplementation(array $implementation) {
    foreach ($implementation as $module => $groups) {
      $this->scrambleImplementationGroup($module, $groups);
    }
  }

  /**
   * Scramble the implementation group.
   *
   * @param string $module
   *   Contains the module name.
   * @param array $groups
   *   Contains the implementation groups structure array.
   */
  public function scrambleImplementationGroup($module, array $groups) {
    foreach ($groups as $group) {
      $object = $this->prepareScramblerObject($module, $group);

      // Execute the method.
      if ($object->execute()) {
        watchdog(
            'scrambler', 'Successful execution of method %m.', array('%m' => $object->method), WATCHDOG_INFO
        );
      }
      else {
        watchdog(
            'scrambler', 'Error while executing function %m.', array('%m' => $object->method), WATCHDOG_ERROR
        );
      }
      // Free up memory.
      unset($object);
    }
  }

  /**
   * Prepare the scrambler object before actual scrambling.
   *
   * @param string $module
   *   Contains the string of the module.
   * @param array $group
   *   Contains the group parameters.
   *
   * @return \Drupal\scrambler\ImplementationObject
   *   Returns an implementation object.
   */
  public function prepareScramblerObject($module, array $group) {
    $object = new ImplementationObject();
    // Name of the module.
    $object->module = $module;
    // Name of the table that contains the fields.
    $object->table = $group['base_table'];
    // Id of the table of the fields.
    $object->id = $group['id'];
    // An array containing the fields.
    $object->fields = $group['fields'];
    if (array_key_exists('master_table', $group)) {
      // Name of the master table that also contains the fields.
      $object->masterTable = $group['master_table'];
    }
    // A string that represents the scramble method.
    $object->method = $this->getMethodName($object->module, $group);

    return $object;
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
  private function getMethodName($module, array $params) {
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
      'scrambler', 'Non-existing function %m(&$data) {}.', array('%m' => $method), WATCHDOG_ERROR
    );

    return FALSE;
  }

}
