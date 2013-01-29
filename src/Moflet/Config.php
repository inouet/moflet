<?php

namespace Moflet;

/**
 * Config
 *
 * @package Moflet
 * @author  Taiji Inoue <inudog@gmail.com>
 */
class Config {

    static $config = array();

    /**
     * Set config value
     * 
     * @param string $key
     * @param string $value
     */
    public static function set($key, $value = null) {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                self::$config[$key] = $value;
            }
        } else {
            self::$config[$key] = $value;
        }
    }

    /**
     * Get config value
     *
     * @param string $key
     * @return mixed
     */
    public static function get($key) {
        if (array_key_exists($key, self::$config)) {
            return self::$config[$key];
        }
        return null;
    }

    /**
     * Get all config value
     *
     * @return array $config 
     */
    public static function all() {
        return self::$config;
    }

    /**
     * Read config file
     *
     * @param  string $file
     * Note: Config file must retun value.
     */
    public static function read($file) {  
        if (is_file($file)) {
            $key   = basename($file, '.php');
            $value = include $file;
            self::set($key, $value);
        }
    }

    /**
     * Read config files in specified directory
     *
     * @param  string $dir
     */
    public static function readDirectory($dir) {
        if (is_dir($dir)) { 
            $files = glob("{$dir}/*.php");
            foreach ($files as $file) {
                self::read($file);
            }
        }
    }

    /**
     * Clear all config data
     *
     */
    public static function clear() {
        self::$config = array();
    }
}

