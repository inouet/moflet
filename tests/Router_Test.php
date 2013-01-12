<?php

require_once("../src/Router.php");

use Moflet\Router;

class Router_Test extends PHPUnit_Framework_TestCase
{

    public function setup() {
    }

    public function testMatch() {

        $router = Router::getInstance();

        $router->add('home', '/', array('controller' => 'home', 'action' => 'index'));
        $router->add('entry_show', '/entry/:id', 
            array('controller' => 'entry', 'action' => 'show')
        );
        $router->add('default', '/:controller/:action');

        // home
        $route = $router->match('/');
        $expect = array('controller' => 'home', 'action' => 'index', 'params' => array());
        $this->assertEquals($expect, $route);
        
        // entry_show
        $route = $router->match('/entry/12');
        $expect = array('controller' => 'entry', 'action' => 'entry_show', 'params' => array('id' => 12));
        $this->assertEquals($expect, $route);

        // default
        $route = $router->match('/news/list?sort=date');
        $expect = array('controller' => 'news', 'action' => 'list', 'params' => array('sort' => 'date'));
        $this->assertEquals($expect, $route);
#        print_r($route);

        $route = $router->match('/news/list?sort=date#header');
        $expect = array('controller' => 'news', 'action' => 'list', 'params' => array());
        $this->assertEquals($expect, $route);
#        print_r($route);
    }
}

