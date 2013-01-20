<?php

use Moflet\Controller;

class BaseController extends Controller {

    public function initialize() {
        $this->view->setLayout('layout.html');
    }
}

