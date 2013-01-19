<?php

namespace Moflet;

/**
 * HTML form utility class
 *
 * @package Moflet
 * @author  Taiji Inoue <inudog@gmail.com>
 */
class Form {

    /**
     * Constructor
     *
     */
    public function __constuct() {
    }

    /**
     * Render input tag
     *
     * Usage:
     *
     * echo $form->input('first_name', $first_name, array('size' => 10, 'id' => 'first_name'));
     *
     * @return string
     */
    public function input($name, $value = '', array $params = array()) {
        $params['type']  = 'text';
        $params['name']  = $name;
        $params['value'] = $value;
        $html  = $this->_render('input', $params);
        return $html;
    }

    /**
     * Render password tag
     *
     * @return string
     */
    public function password($name, $value = '', array $params = array()) {
        $params['type']  = 'password';
        $params['name']  = $name;
        $params['value'] = $value;
        $html  = $this->_render('input', $params);
        return $html;
    }

    /**
     * Render hidden tag
     *
     * @return string
     */
    public function hidden($name, $value = '', array $params = array()) {
        $params['type']  = 'hidden';
        $params['name']  = $name;
        $params['value'] = $value;
        return $this->_render('input', $params);
    }

    /**
     * Render textarea tag
     *
     * @return string
     */
    public function textarea($name, $value = '', array $params = array()) {
        $params['name']  = $name;
        return $this->_render('textarea', $params, (string) $value, true);
    }

    /**
     * Render select tag
     *
     * @return string
     */
    public function select($name, array $options, $selected = null,
                                                array $params = array()) {
        $params['name'] = $name;
        $_options = array();
        foreach ($options as $_value => $_name) {
            $_params = array('value' => $_value);
            if (strcmp($selected, $_value) === 0) {
                $_params['selected'] = 'selected';
            }
            $_options[] = $this->_render('option', $_params, $_name);
        }
        $element = join(PHP_EOL, $_options);
        return $this->_render('select', $params, $element, false);
    }

    /**
     * Render checkbox tag
     *
     * @return string
     */
    public function checkbox($name, $value = '', array $params = array()) {
        $params['type']  = 'checkbox';
        $params['name']  = $name;
        $params['value'] = $value;
        return $this->_render('input', $params);
    }

    /**
     * Render checkbox tags
     *
     * Usage:
     *
     * $options = array(1 => 'baseball', 2 => 'soccor', 3 => 'golf');
     * $checked = array(2, 3);  // soccor and golf is checked
     * $params  = array('separator' => '<br />'.PHP_EOL);
     * echo $form->checkboxList('sports', $options, $checked, $params) .PHP_EOL;
     */
    public function checkboxList($name, array $options, $checked = '',
                                            array $params = array()) {
        $html = array();
        $separator = '';
        if (array_key_exists('separator', $params) && $params['separator']) {
            $separator = $params['separator'];
            unset($params['separator']);
        }
        $params['type'] = 'checkbox';
        $params['name'] = $name . '[]';

        if (is_scalar($checked) && strlen($checked) > 0) {
            $checked = array($checked);
        } elseif (!is_array($checked)) {
            $checked = array();
        }
        $i = 1;
        foreach ($options as $_value => $_name) {
            $params['value'] = $_value;
            $params['id'] = 'form_'. $name . '_' . $i++;
            if (in_array($_value, $checked)) {
                $params['checked'] = 'checked';
            } else {
                unset($params['checked']);
            }
            $input_tag = $this->_render('input', $params);
            $html[] = $this->_render('label', array('for' => $params['id']),
                                    $input_tag . $_name, false);
        }
        return join($separator, $html);
    }

    /**
     * Render radio tag
     *
     * @return string
     */
    public function radio($name, $value, array $params = array()) {
        $params['type'] = 'radio';
        $params['name'] = $name;
        $params['value'] = $value;
        return $this->_render('input', $params);
    }

    /**
     * Render radio tags
     *
     * Usage:
     *
     * $options = array(1 => 'baseball', 2 => 'soccor', 3 => 'golf');
     * $checked = 2;  // soccor is checked
     * $params  = array('separator' => '<br />'.PHP_EOL);
     * echo $form->radioList('sports', $options, $checked, $params) .PHP_EOL;
     */
    public function radioList($name, array $options, $checked = null,
                                            array $params = array()) {
        $html = array();
        $separator = '';
        if (array_key_exists('separator', $params) && $params['separator']) {
            $separator = $params['separator'];
            unset($params['separator']);
        }
        $params['type'] = 'radio';
        $params['name'] = $name;
        $i = 1;
        foreach ($options as $_value => $_name) {
            $params['value'] = $_value;
            $params['id'] = 'form_'. $name . '_' . $i++;
            if (strcmp($checked, $_value) === 0) {
                $params['checked'] = 'checked';
            } else {
                unset($params['checked']);
            }
            $input_tag = $this->_render('input', $params);
            $html[] = $this->_render('label', array('for' => $params['id']),
                                    $input_tag . $_name, false);
        }
        return join($separator, $html);
    }

    /**
     * Render file tag
     *
     * @return string
     */
    public function file($name, $value = null, array $params = array()) {
        $params['type']  = 'file';
        $params['name']  = $name;
        $params['value'] = $value;
        $html  = $this->_render('input', $params);
        return $html;
    }

    /**
     * Render submit tag
     *
     * @return string
     */
    public function submit($name, $value = 'Submit', array $params = array()) {
        $params['type']  = 'submit';
        $params['name']  = $name;
        $params['value'] = $value;
        return $this->_render('input', $params);
    }

    /**
     * Render button tag
     *
     * @return string
     */
    public function button($name, $value = 'Button', array $params = array()) {
        $params['type']  = 'button';
        $params['name']  = $name;
        $params['value'] = $value;
        return $this->_render('input', $params);
    }

    /**
     * Render html
     *
     * @return string
     */
    private function _render($tag, array $attr, $element = null,
                               $escape_element = true) {
        $html= "<{$tag}";
        foreach ($attr as $key => $value) {
            if ($value === null) {
                $html .= " {$key}";
            } else {
                $html .= " {$key}=".'"'.
                    htmlspecialchars($value, ENT_QUOTES).'"';
            }
        }
        if ($element === null) {
            $html .= ' />';
        } else if ($escape_element) {
            $html .= '>'.htmlspecialchars($element, ENT_QUOTES). "</{$tag}>";
        } else {
            $html .= ">{$element}</{$tag}>";
        }
        return $html;
    }
}


