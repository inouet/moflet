<?php

require_once __DIR__."/../src/Moflet/Config.php";

use Moflet\Config;

class Config_Test extends PHPUnit_Framework_TestCase {

    public function setup() {
    }

    public function testSet() {

        Config::set('host', 'localhost');
        Config::set('db', 'test');

        $host = Config::get('host');
        $this->assertEquals($host, 'localhost');
        $db = Config::get('db');
        $this->assertEquals($db, 'test');

    }

    public function testRead() {
        // Read File
        Config::read(__DIR__. '/config/cache.php');

        $res = Config::get('cache');
        $cache = array("dir" => "/tmp", "lifetime" => 3600);

        $this->assertEquals($cache, $res);

        // Read Directory
        Config::clear();

        $routing['home'] = array(
                '/', 
                    array('controller' => 'home', 'action' => 'index')
                );

        $expect = array(
            'routing' => $routing,
            'cache' => $cache,
        );

        Config::readDirectory(__DIR__. '/config/');
        $config = Config::all();

        $this->assertEquals($expect, $config);

    }

}
