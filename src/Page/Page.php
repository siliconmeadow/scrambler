<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Page
 *
 * @author Greg Bakos <greg@londonfreelancers.co.uk>
 */
namespace Drupal\scrambler\Page;

class Page {
  public function getMenuItems() {
    return array(
      'admin/config/development/scrambler/fields' =>  array(
        'title' => 'Configure Fields Scramble',
        'description' => 'Configure which fields need to be scrambled.',
        'page callback' => 'scrambler_administer_fields_page',
        'type' => MENU_NORMAL_ITEM,
        'access arguments' => array('administer scrambler fields'),
        'file' => 'includes/scrambler.admin.inc',
        'file path' => drupal_get_path('module', 'scrambler'),
      ),
      'admin/config/development/scrambler/general' => array(
        'title' => 'Configure General Scramble',
        'description' => 'Configure general settings for scrambling.',
        'page callback' => 'drupal_get_form',
        'page arguments' => array('scrambler_general_settings_form'),
        'type' => MENU_NORMAL_ITEM,
        'access arguments' => array('administer scrambler'),
        'file' => 'includes/scrambler.admin.inc',
        'file path' => drupal_get_path('module', 'scrambler'),
      ),
    );
  }
}
