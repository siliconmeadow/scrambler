<?php
/**
 * @file
 * Scramble Controller.
 */

namespace Drupal\scrambler\Controller;

/**
 * Class ScrambleController.
 *
 * @package Drupal\scrambler\Controller
 */
class ScrambleController extends ConfigController {

  /**
   * Get all implementations of the scrambler api.
   *
   * @return array
   *   Returns an array of parameters of all implementations.
   */
  public function getImplementations() {
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

  /**
   * Get all the methods of the scrambler module and other modules.
   *
   * @return array
   *   Returns an array of methods.
   */
  public function getMethods() {
    $implementing_modules = module_implements('scrambler_methods');
    $methods = array();

    foreach ($implementing_modules as $module) {
      $function = $module . '_scrambler_methods';
      $module_methods = $function();
      foreach ($module_methods as &$method) {
        $method = t($method);
      }
      $methods += $module_methods;
    }

    return $methods;
  }
}
