<?php

namespace Moflet;

/**
 * View class
 *
 * @package Moflet
 * @author  Taiji Inoue <inudog@gmail.com>
 */
class View {

    private $data = array();

    private $data_raw = array();

    private $layout;

    private $template_dir;

    /**
     * Set variables
     *
     * @param  string|array $key
     * @param  mixed        $value
     */
    public function set($key, $value = null) {
        if (is_array($key)) {
            foreach ($key as $_key => $_value) {
                $this->_set($_key, $_value);
            }
        } else {
            $this->_set($key, $value);
        }
    }

    /**
     * Render template file
     *
     * @param  strring $template
     * @return string $contents
     */
    public function render($template) {
        extract($this->data);

        if (!is_file($this->template_dir . '/'. $template)) {
            return false;
        }
        
        ob_start();
        include $this->template_dir . '/'. $template;
        $contents = ob_get_clean();

        if ($this->layout && is_file($this->template_dir.'/'.$this->layout)) {
            ob_start();
            include $this->template_dir.'/'.$this->layout;
            $contents = ob_get_clean();
        }
        return $contents;
    }

    /**
     * Set template directory
     * 
     * @param string $template_dir
     */
    public function setTemplateDir($template_dir) {
        $this->template_dir = $template_dir;
    }

    /**
     * Set layout file
     *
     * @param string $layout
     */
    public function setLayout($layout) {
        $this->layout = $layout;
    }

    /**
     * Execute htmlspecialchars recursively
     *
     * @param mixed  $mixed
     * @param int    $quote_style
     * @param string $charset
     */
    public function htmlspecialcharsDeep($mixed, $quote_style = ENT_QUOTES, $charset = 'UTF-8') {
        if (is_array($mixed)) { 
            foreach ($mixed as $key => $value) { 
                $mixed[$key] = $this->htmlspecialcharsDeep($value, $quote_style, $charset); 
            } 
        } elseif (is_string($mixed)) { 
            $mixed = htmlspecialchars(htmlspecialchars_decode($mixed, $quote_style), $quote_style, $charset); 
        } 
        return $mixed; 
    }  

    private function _set($key, $value) {
        $escaped_value = $this->htmlspecialcharsDeep($value);
        $this->data_raw[$key] = $value;
        $this->data[$key] = $escaped_value;
    }
}
