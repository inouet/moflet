<?php

require_once 'BaseController.php';

class HomeController extends BaseController {

    public function indexAction(array $input) {

        $this->view->setTemplate('home/index.html');
        return $this->view;
    }
}

