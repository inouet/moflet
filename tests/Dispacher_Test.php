<?php

require_once "../src/Moflet/Dispacher.php";

use Moflet\Dispacher;

class Dispacher_Test extends PHPUnit_Framework_TestCase {

    public function setup() {
    }

    public function testGetControllerClassName() {

        $class = new Dispacher();

        $check_list = array(
                            'user' => 'UserController',
                            'admin_home' => 'AdminHomeController',
                            );
        foreach ($check_list as $string => $expect) {
            $name  = $class->getControllerClassName($string);
            $this->assertEquals($expect, $name);
        }
    }

}
