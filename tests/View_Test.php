<?php

require_once __DIR__."/../src/Moflet/View.php";

use Moflet\View;

class View_Test extends PHPUnit_Framework_TestCase {

    public function setup() {
    }

    public function testRender() {
        $view = new View();
        $template = "test01.html";

        $view->setTemplateDir(__DIR__.'/view');
        $view->setTemplate($template);
        $view->set('name', 'John');
        $hash = array("age" => 20, "sex" => 1);
        $view->set('hash', $hash);
        
        $html = $view->render();

        $expect = "Hello John!\nage:20\nsex:1\n";

        $this->assertEquals($expect, $html);

        $view->setLayout("layout.html");
        $html = $view->render($template);

        $expect = "<html><title>Hello John</title>{$expect}</html>";

    }
}
