<?php
/**
 * @file
 *   Contains the configuration controllers.
 */

namespace Drupal\scrambler_field\Controller;

/**
 * Configuration controller class.
 */
class ConfigController {

  /**
   * Get all the configuration menu items.
   *
   * @return array
   *   Returns an array of configuration menu items.
   */
  public function getMenuItems() {
    return array(
      'admin/config/development/scrambler/fields' =>  array(
        'title' => 'Configure Fields Scrambler',
        'description' => 'Configure which fields need to be scrambled.',
        'page callback' => 'scrambler_field_administer_fields_page',
        'weight' => -10,
        'type' => MENU_LOCAL_TASK,
        'access arguments' => array('administer scrambler fields'),
        'file' => 'includes/scrambler_field.admin.inc',
        'file path' => drupal_get_path('module', 'scrambler_field'),
      ),
    );
  }
}
