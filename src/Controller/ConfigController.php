<?php
/**
 * @file
 */

namespace Drupal\scrambler\Controller;

/**
 * Class ConfigController
 * @package Drupal\scrambler\Controller
 * @inheritdoc
 */
class ConfigController {
  /**
   * Main router entry.
   * @return array
   */
  public static function getMenuItems() {
    return array(
      'admin/config/development/scrambler' => array(
        'title' => t('Configure Scrambler'),
        'description' => t('Configure Scrambling.'),
        'page callback' => 'scrambler_administration_page',
        'type' => MENU_NORMAL_ITEM,
        'access arguments' => array('administer scrambler'),
        'file' => 'includes/scrambler.admin.inc',
        'file path' => drupal_get_path('module', 'scrambler'),
      ),
      'admin/config/development/scrambler/execute' => array(
        'title' => t('Execute Scramble methods'),
        'description' => t('Configure Scrambling.'),
        'page callback' => 'drupal_get_form',
        'page arguments' => array('scrambler_administration_execute_page'),
        'type' => MENU_LOCAL_TASK,
        'access arguments' => array('administer scrambler'),
        'file' => 'includes/scrambler.admin.inc',
        'file path' => drupal_get_path('module', 'scrambler'),
      ),
    );
  }
}
