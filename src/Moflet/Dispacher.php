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
            
            $controller = $this->getController($route['controller']);
            $controller->initialize();

            $action_name = $this->getActionName($route['action']);
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

    /**
     * Get Controller class instance
     *
     * @param string $controller
     * @return \Moflet\Controller
     */
    public function getController($controller) {
        $controller_name = $this->getControllerClassName($controller);
            
        $controller_file = MOF_CONTROLLER_DIR . '/'. $controller_name . '.php';
        if (file_exists($controller_file)) {
            require_once($controller_file);
        }
        
        if (!class_exists($controller_name)) {
            throw new \Exception('Controller Not Found: '. $controller_name);
        }
        $controller = new $controller_name();
        return $controller;
    }

    /**
     * Get controller class name
     * 
     * @param  string $string
     * @return string $class_name  ex) user_home -> UserHomeController
     */
    public function getControllerClassName($string) {
        $class_name = sprintf('%sController', $this->toCamelCase($string, true));
        return $class_name;
    }

    /**
     * Get action method name
     * 
     * @param  string $string
     * @return string $action_name  ex) profile_input -> profileInputAction
     */
    public function getActionName($string) {
        $action_name = sprintf('%sAction', $this->toCamelCase($string));
        return $action_name;
    }
    
    /**
     * Get Router instance
     *
     * @return \Moflet\Router
     */
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
