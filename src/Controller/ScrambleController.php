<?php
/**
 * @file
 *
 */
namespace Drupal\scrambler\Controller;

/**
 * Class ScrambleController
 * @package Drupal\scrambler\Controller
 */
class ScrambleController extends ConfigController {
  function getImplementations() {
    $implementing_modules = module_implements('scrambler_api');

    foreach ($implementing_modules as $module) {
      $function = $module . '_scrambler_api';
      // Will call all modules implementing hook_hook_name.
      // And can pass each argument as reference determined.
      // By the function declaration.
      $parameters[] = $function();
    }
    return $parameters;
  }
}
