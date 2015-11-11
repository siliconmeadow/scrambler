<?php
/**
 * @file
 * API class for the scramble module.
 */

namespace Drupal\scrambler\BLL;

use Drupal\scrambler\Controller;

/**
 * API class object.
 */
class API {
  /**
   * API class constructor.
   */
  public function __construct() {
  }

  /**
   * Start scrambling the database.
   */
  public function scramble() {
    $parameters = new Controller\ScrambleController();

    // @todo: Check for implementations here with $parameters->getImplementations()

    return true;
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
