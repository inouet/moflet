<?php

namespace Moflet;

/**
 * URL Router
 *
 * @package Moflet
 * @author  Taiji Inoue <inudog@gmail.com>
 */
class Router {

    protected $name;

    protected $paths = array();

    private static $instances = array();

    /**
     * Constructor
     *
     * Constructor is private, you should use getInstance() instead.
     */
    private function __construct() { }

    /**
     * Returns a singleton object
     *
     * @param  string    Requested instance name
     * @return object    Router Singleton
     */
    public static function getInstance($name = 'default') {
        if (!isset(self::$instances[$name])) {
            $object = new self();
            $object->name = $name;
            self::$instances[$name] = $object;
        }
        return self::$instances[$name];
    }

    public function add($name, $path, $defaults = array(), $rules = array()) {
        $path = new Router_Path($path, $defaults, $rules);
        $this->paths[$name] = $path;
    }

    public function match($uri) {

        $get_params = array();

        if (strpos($uri, '#') !== false) {
            list($uri,) = explode('#', $uri);
        }

        if (strpos($uri, '?') !== false) {
            list($uri, $str) = explode('?', $uri);
            parse_str($str, $get_params);
        }

        foreach ($this->paths as $path) {
            $matches = $path->match($uri);
            if ($matches !== false) {
                $defaults     = $path->getDefaults();
                $named_params = $path->getNamedParams();
                return $this->_format($matches, $defaults, $named_params, $get_params);
            }
        }
        return false;
    }

    private function _format($matches, $defaults, $named_params, $get_params) {
        $controller = '';
        $action     = '';
        $params     = array();

        // controller
        if (array_key_exists('controller', $named_params)) {
            $controller = $matches[$named_params['controller']];
            unset($named_params['controller']);
        } elseif (array_key_exists('controller', $defaults)) {
            $controller = $defaults['controller'];
        } elseif ($matches[0]) {
            $controller = $matches[0];
        }

        // action
        if (array_key_exists('action', $named_params)) {
            $action = $matches[$named_params['action']];
            unset($named_params['action']);
        } elseif (array_key_exists('action', $defaults)) {
            $action = $defaults['action'];
        } elseif (@$matches[1]) {
            $action = $matches[1];
        }

        // params
        foreach ($named_params as $name => $index) {
            $params[$name] = $matches[$index];
        }

        // merge get params
        foreach ($get_params as $key => $val) {
            if (!array_key_exists($key, $params)) {
                $params[$key] = $get_params[$key];
            }
        }

        $result = array('controller' => $controller,
                        'action'     => $action,
                        'params'     => $params,
                        );
        return $result;
    }

    /**
     * Display all rules for debugging.
     *
     */
    public function dump() {
        foreach ($this->paths as $name => $path) {
            echo "--------------------".PHP_EOL;
            echo "name: {$name}".PHP_EOL;
            echo "path: ". $path->getPath().PHP_EOL;
            echo "pattern: ".$path->getPattern(). PHP_EOL;
            echo "rules:".PHP_EOL;
            print_r($path->getRules());
            echo "defaults:".PHP_EOL;
            print_r($path->getDefaults());
        }
    }
}

class Router_Path {

    private $path;

    private $defaults = array();

    private $rules = array();

    private $named_params = array();

    private $pattern;

    public function __construct($path, array $defaults = array(), array $rules = array()) {
        $this->path = $path;
        $this->defaults = $defaults;
        $this->rules = $rules;
        $this->_initialize();
    }

    private function _initialize() {
        
        if ($this->path === '/' || $this->path === '') {
            $this->pattern = '|^/$|';
            return;
        }

        $parts = explode('/', trim($this->path, '/'));
        $list = array();

        foreach ($parts as $i => $part) {
            if (strlen($part) == 0) {
                $list[] = "";
            } elseif (preg_match('/^:([a-zA-Z0-9_]+)/', $part, $matches)) {
                $this->named_params[$matches[1]] = $i;

                // TODO: rules
                $list[] = '([a-zA-Z0-9_]+)';
            } elseif (preg_match('/^\*([a-zA-Z0-9_]+)/', $part, $matches)) {
                $this->named_params[$matches[1]] = $i;
                $list[] = '(.+)';
            } else {
                $list[] = "({$part})";
            }
        }
        $this->pattern = '|^/'.join('/', $list). '/?$|';
    }

    public function match($uri) {
        if (preg_match($this->pattern, $uri, $matches)) {
            array_shift($matches);
            return $matches;
        }
        return false;
    }

    public function getPath() {
        return $this->path;
    }

    public function getNamedParams() {
        return $this->named_params;
    }

    public function getDefaults() {
        return $this->defaults;
    }

    public function getPattern() {
        return $this->pattern;
    }

    public function getRules() {
        return $this->rules;
    }
}

