<?php

namespace Moflet;

require_once 'Router.php';
require_once 'Controller.php';

/**
 * Dispacher
 *
 * @package Moflet
 * @author  Taiji Inoue <inudog@gmail.com>
 */
class Dispacher {

    public function dispatch () {
        try {
            $html   = null;
            $uri    = $this->getRequestUri();
            $router = $this->getRouter();

            $route = $router->match($uri);
            if ($router === false || !isset($route['controller']) || !isset($route['action'])) {
                throw new \Exception('No route found');
            }
            
            $controller_class = sprintf('%sController', $this->toCamelCase($route['controller'], true));
            $action_name = sprintf('%sAction', $this->toCamelCase($route['action']));
            
            $controller_class_file = MOF_CONTROLLER_DIR . '/'. $controller_class . '.php';
            if (file_exists($controller_class_file)) {
                require_once(MOF_CONTROLLER_DIR . '/'. $controller_class . '.php');
            }
            
            if (!class_exists($controller_class)) {
                throw new \Exception('Controller Not Found: '. $controller_class);
            }
            
            $controller = new $controller_class();
            $controller->initialize();
            if (!method_exists($controller, $action_name)) {
                throw new \Exception('Method Not Found: '. $action_name);
            }
            $view = $controller->$action_name($route['params']);
            
            if (method_exists($view, 'render')) {
                $html = $view->render();
            }
            echo $html;
        } catch (\Exception $e) {
            // TODO
            echo "ERROR: ". $e->getMessage()."\n";
        }
    }

    protected function getRouter() {
        $config = include MOF_CONFIG_DIR . '/routing.php';

        $router = Router::getInstance();
        foreach ($config as $name => $setting) {
            if (!isset($setting[2])) {
                $setting[2] = array();
            }
            $router->add($name, $setting[0], $setting[1], $setting[2]);
        }
        return $router;
    }

    protected function getRequestUri() {
        $path = '/';
        if (isset($_SERVER['PATH_INFO'])) {
            $path = $_SERVER['PATH_INFO'];
        } elseif (isset($_SERVER['REQUEST_URI'])) {
            $path = $_SERVER['REQUEST_URI'];
        }
        return $path;
    }

    private function toCamelCase($str, $ucfirst = false) {
        $parts = explode('_', $str);
        $parts = $parts ? array_map('ucfirst', $parts) : array($str);
        $parts[0] = $ucfirst ? ucfirst($parts[0]) : lcfirst($parts[0]);
        return implode('', $parts);
    }
}
