<?php
/**
 * @file
 * Work with permissions.
 */

namespace Drupal\scrambler_field\Security;

/**
 * Class Permission.
 *
 * @package Drupal\scrambler_field\Security
 */
class Permission {
  /**
   * Define permissions for Drupal.
   *
   * @return array
   *   Array with permissions.
   */
  public function getPermission() {
    return array(
      'administer scrambler fields' => array(
        'title' => t('Administer scrambler fields'),
        'description' => t('Configure the fields to be sanitized.'),
      ),
    );
  }

}
