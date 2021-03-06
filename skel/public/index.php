<?php
/**
 * Moflet Front Controller
 *
 */

define('MOF_APP_DIR', dirname(__DIR__));

define('MOF_VIEW_DIR', MOF_APP_DIR . '/view');
define('MOF_CONFIG_DIR', MOF_APP_DIR . '/config');
define('MOF_CONTROLLER_DIR', MOF_APP_DIR . '/controller');
define('MOF_MODEL_DIR', MOF_APP_DIR . '/model');
define('MOF_TMP_DIR', MOF_APP_DIR . '/var/tmp');
define('MOF_LOG_DIR', MOF_APP_DIR . '/var/logs');
define('MOF_CACHE_DIR', MOF_APP_DIR . '/var/cache');

define('MOF_CORE_DIR', MOF_APP_DIR . '/vendor/moflet/moflet/src');

// Use Composer autoloader
$loader = include MOF_APP_DIR . '/vendor/autoload.php';
$loader->add(null, MOF_MODEL_DIR);
$loader->add(null, MOF_CONTROLLER_DIR);

$dispacher = new Moflet\Dispacher();
$dispacher->dispatch();
