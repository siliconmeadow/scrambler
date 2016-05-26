  ____                           _     _
 / ___|  ___ _ __ __ _ _ __ ___ | |__ | | ___ _ __
 \___ \ / __| '__/ _` | '_ ` _ \| '_ \| |/ _ \ '__|
  ___) | (__| | | (_| | | | | | | |_) | |  __/ |
 |____/ \___|_|  \__,_|_| |_| |_|_.__/|_|\___|_|

 Helps you prevent exposing sensitive information from the database.

* Introduction
* Important notice
* Requirements
* Installation
* Configuration
* Usage
* FAQ
* Maintainers

INTRODUCTION
------------

The Scrambler module is an API that helps you to prevent exposing sensitive
information from the database. It also contains the Scrambler Field submodule
which makes it able to administer which scramble methods to apply per field.

 * For a full description of the module, visit the project page:
   https://www.drupal.org/project/scrambler

 * To submit bug reports and feature suggestions, or to track changes:
   https://drupal.org/project/issues/scrambler

IMPORTANT NOTICE
----------------

Do not enable the Scrambler module on a production or staging environment. In
case of accidental execution of the scrambling procedure there is no way back.
Your data will then be damaged.

Do only enable the Scrambler module on a syst or test environment.

REQUIREMENTS
------------

This module requires the following modules:

 * X Autoload (https://www.drupal.org/project/xautoload)

The submodule scrambler_field requires the following modules:

 * Entity API (https://www.drupal.org/project/entity)
 * Scrambler (https://www.drupal.org/project/scrambler)
 * Views (https://www.drupal.org/project/views)
 * Views Bulk Operations (https://www.drupal.org/project/views_bulk_operations)
 * X Autoload (https://www.drupal.org/project/xautoload)

INSTALLATION
------------

 * Install as you would normally install a contributed Drupal module. See:
   https://drupal.org/documentation/install/modules-themes/modules-7
   for further information.

CONFIGURATION
-------------

  * Configure user permissions in Administration » People » Permissions:

   - Administer scrambler fields (Scrambler Field module)

     The top-level administration categories require this permission
     to be accessible.

 * Configure main settings in Administration » Configuration and modules »
   Development » Scrambler.

 * Configure Fields Scrambling in Administration » Configuration and modules »
   Scrambler » Configure Fields Scrambler.


USAGE
-----

Execution of scrambling can be done through UI

 * See Administration » Configuration and modules » Development » Scrambler »
   Execute Scramble Methods.

or through the following Drush command (more information of Drush
can be found on https://www.drupal.org/documentation/modules/drush)

 * drush scramble

FAQ
---
Q: Is it possible to scramble a custom table?

A: You can scramble a custom table by implementing the hook_scrambler_api. For
   more information on how to implement this consult the submodule Scrambler
   Example or the project page on https://www.drupal.org/project/scrambler.

Q: Can I apply my own scrambling method?

A: Yes it is possible to apply your own scrambling method. First check for the
   current available methods from the Scrambler API. For more information on
   how to implement this consult the submodule Scrambler Example or
   the project page on https://www.drupal.org/project/scrambler.


MAINTAINERS
-----------

Current maintainers:
 * Nico Knaepen (nico.knaepen) - https://www.drupal.org/u/nico.knaepen
 * Kevin Thiels (Novitsh) - https://www.drupal.org/u/novitsh

This project has been sponsored by:
 * LOGIC IN MOTION
   A young dynamic company focusing on digital marketing.
   Visit http://www.logicinmotion.be for more information.

 * COLRUYT GROUP
