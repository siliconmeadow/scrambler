<?php
/**
 * @file
 * Contains config controller.
 */

namespace Drupal\scrambler_ui\Controller;

/**
 * Class ConfigController.
 *
 * @package Drupal\scrambler_ui\Controller
 */
class ConfigController {
  /**
   * Main router entry.
   */
  public static function getMenuItems() {
    return array(
      'admin/config/development/scrambler/execute' => array(
        'title' => t('Execute Scramble methods'),
        'description' => t('Configure Scrambling.'),
        'page callback' => 'drupal_get_form',
        'page arguments' => array('scrambler_ui_administration_execute_page'),
        'type' => MENU_LOCAL_TASK,
        'access arguments' => array('administer scrambler'),
        'file' => 'includes/scrambler_ui.admin.inc',
        'file path' => drupal_get_path('module', 'scrambler_ui'),
      ),
    );
  }

}
