<?php
/**
 * Moflet Front Controller
 *
 */

define("MOF_ROOT_DIR", dirname(__DIR__));
define("MOF_VIEW_DIR", MOF_ROOT_DIR . '/view');
define("MOF_CONFIG_DIR", MOF_ROOT_DIR . '/config');
define("MOF_CONTROLLER_DIR", MOF_ROOT_DIR . '/controller');

require_once(MOF_ROOT_DIR . "/../src/Moflet/Dispacher.php");

$dispacher = new Moflet\Dispacher();
$dispacher->dispatch();

