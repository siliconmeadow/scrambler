<?php
/**
 * @file
 *   Contains the configuration controllers.
 */

namespace Drupal\scrambler\Controller;

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
      'admin/config/development/scrambler' => array(
        'title' => t('Configure Scrambler'),
        'description' => t('Configure Scrambling.'),
        'page callback' => 'drupal_get_form',
        'page arguments' => array('scrambler_settings_form'),
        'type' => MENU_NORMAL_ITEM,
        'access arguments' => array('administer scrambler'),
        'file' => 'includes/scrambler.admin.inc',
        'file path' => drupal_get_path('module', 'scrambler'),
      ),
      'admin/config/development/scrambler/fields' =>  array(
        'title' => t('Configure Fields Scrambler'),
        'description' => t('Configure which fields need to be scrambled.'),
        'page callback' => 'scrambler_administer_fields_page',
        'access arguments' => array('administer scrambler fields'),
        'file' => 'includes/scrambler.admin.inc',
        'file path' => drupal_get_path('module', 'scrambler'),
      ),
      'admin/config/development/scrambler/general' => array(
        'title' => t('Configure General Scrambler'),
        'description' => t('Configure general settings for scrambling.'),
        'page callback' => 'drupal_get_form',
        'page arguments' => array('scrambler_general_settings_form'),
        'access arguments' => array('administer scrambler'),
        'file' => 'includes/scrambler.admin.inc',
        'file path' => drupal_get_path('module', 'scrambler'),
      ),
    );
  }
}
