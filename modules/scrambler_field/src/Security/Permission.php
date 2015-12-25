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
namespace Drupal\scrambler_field\Security;

class Permission {
  public function getPermission() {
    return array(
      'administer scrambler fields' => array(
        'title' => t('Administer scrambler fields'),
        'description' => t('Configure the fields to be sanitized.'),
      ),
    );    
  }
}
