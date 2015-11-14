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
  function getMenuItems() {
    return array(
      'admin/config/development/scrambler' => array(
        'title' => t('Configure Scrambler'),
        'description' => t('Configure Scrambling.'),
        'page callback' => 'scrambler_administration_page',
        'type' => MENU_NORMAL_ITEM,
        'access arguments' => array('administer scrambler'),
        'file' => 'includes/scrambler.admin.inc',
        'file path' => drupal_get_path('module', 'scrambler'),
      )
    );
  }
}
