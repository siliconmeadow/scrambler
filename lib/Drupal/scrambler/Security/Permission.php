<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Permission
 *
 * @author Greg Bakos <greg@londonfreelancers.co.uk>
 */
namespace Drupal\scrambler\Security;

class Permission {
  public function getPermission() {
    return array(
      'administer scrambler' => array(
        'title' => t('Administer scrambler'),
        'description' => t('Configure the way content needs to be sanitized.'),
      ),
      'administer scrambler fields' => array(
        'title' => t('Administer scrambler fields'),
        'description' => t('Configure the fields to be sanitized.'),
      ),
    );    
  }
}
