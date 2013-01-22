<?php
/**
 * Moflet Front Controller
 *
 */

define('MOF_APP_DIR', dirname(__DIR__));
define('MOF_VIEW_DIR', MOF_APP_DIR . '/view');
define('MOF_CONFIG_DIR', MOF_APP_DIR . '/config');
define('MOF_CONTROLLER_DIR', MOF_APP_DIR . '/controller');

//define('MOF_CORE_DIR', '../../src/');
define('MOF_CORE_DIR', '{MOF_CORE_DIR}');
require_once(MOF_CORE_DIR . '/Moflet/Dispacher.php');

$dispacher = new Moflet\Dispacher();
$dispacher->dispatch();

