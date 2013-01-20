<?php
/**
 * Routing configuration file
 */

$routing = array();

$routing['home'] = array(
    '/', 
    array('controller' => 'home', 'action' => 'index')
);

return $routing; // Don't delete this line
