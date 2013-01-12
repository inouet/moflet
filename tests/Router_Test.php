<?php

require_once("../src/Router.php");

use Moflet\Router;

class Router_Test extends PHPUnit_Framework_TestCase {

    public function setup() {
    }

    public function testMatch() {

        $router = Router::getInstance();

        $router->add('home', '/', 
                     array('controller' => 'home', 'action' => 'index')
                     );
        $router->add('entry_show', '/entry/:id', 
                     array('controller' => 'entry', 'action' => 'show')
                     );

        $router->add('blog_archive_show', '/blog/archives/:year/:month/',
                     array('controller' => 'archive', 'action' => 'show')
                     );
        $router->add('default', '/:controller/:action');

        $router->dump();

        // home
        $expect = array('controller' => 'home', 'action' => 'index', 
                        'params' => array());
        $route = $router->match('/');
        $this->assertEquals($expect, $route);
        
        $expect = array('controller' => 'home', 'action' => 'index', 
                        'params' => array('sort' => 'id'));
        $route = $router->match('/?sort=id#home');
        $this->assertEquals($expect, $route);

        $expect = false;
        $route = $router->match('/index.php');
        $this->assertEquals($expect, $route);


        // entry_show
        $expect = array('controller' => 'entry', 'action' => 'show', 
                        'params' => array('id' => 12));
        $route = $router->match('/entry/12');
        $this->assertEquals($expect, $route);
      
        $route = $router->match('/entry/12/');
        $this->assertEquals($expect, $route);

        // archive_show
        $expect = array('controller' => 'archive', 'action' => 'show', 
                        'params' => array('year' => '2012',
                                          'month' => '01',
                                          ));
        $route = $router->match('/blog/archives/2012/01/');
        $this->assertEquals($expect, $route);

        // default
        $expect = array('controller' => 'news', 'action' => 'list', 
                        'params' => array('sort' => 'date'));
        $route = $router->match('/news/list?sort=date');
        $this->assertEquals($expect, $route);

        $route = $router->match('/news/list?sort=date#header');
        $expect = array('controller' => 'news', 'action' => 'list', 
                        'params' => array('sort' => 'date'));
        $this->assertEquals($expect, $route);

        // no match
        $route = $router->match('/index.php');
        $expect = false;
        $this->assertEquals($expect, $route);

    }
}
