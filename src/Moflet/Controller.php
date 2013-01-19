<?php

namespace Moflet;

require_once 'View.php';
require_once 'Form.php';

/**
 * Base controller class
 *
 * @package Moflet
 * @author  Taiji Inoue <inudog@gmail.com>
 */
class Controller {

    protected $view;

    public function __construct() {
        $this->view = new View();
        $this->view->setTemplateDir(MOF_VIEW_DIR);
        $this->view->set('form', new Form());
    }

    public function initialize() {
    }

    protected function redirect($url) {
        if (!headers_sent()) {
            header("Location: {$url}");
        } else { 
            echo '<script type="text/javascript">';
            echo 'window.location.href="'.$url.'";';
            echo '</script>'.PHP_EOL;
            echo '<noscript>';
            echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
            echo '</noscript>'.PHP_EOL;
        }
        exit;
    }

}
