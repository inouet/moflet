<?php

namespace Moflet;

/**
 * Pager
 *
 */
class Pager {

    protected $per_page = 20;

    protected $page = null;

    protected $total_items = 0;

    protected $delta = 5;

    protected $page_parameter_name = 'page';

    protected $base_url;

    protected $template = array(
        'wrapper'           => "<div class=\"pagination\">\n\t<ul>{pagination}\n\t</ul>\n</div>\n",
        'first'             => "\n\t\t<li><a href=\"{uri}\">&lt;&lt;</a></li>",
        'first-inactive'    => "\n\t\t<li class=\"disabled\"><a href=#>&lt;&lt;</a></li>",
        'previous'          => "\n\t\t<li><a href=\"{uri}\">&lt;</a></li>",
        'previous-inactive' => "\n\t\t<li class=\"disabled\"><a href=#>&lt;</a></li>",
        'regular'           => "\n\t\t<li><a href=\"{uri}\">{page}</a></li>\n",
        'active'            => "\n\t\t<li class=\"active\"><a href=#>{page}</a></li>",
        'next'              => "\n\t\t<li><a href=\"{uri}\">&gt;</a></li>",
        'next-inactive'     => "\n\t\t<li class=\"disabled\"><a href=#>&gt;</a></li>",
        'last'              => "\n\t\t<li><a href=\"{uri}\">&gt;&gt;</a></li>",
        'last-inactive'     => "\n\t\t<li class=\"disabled\"><a href=#>&gt;&gt;</a></li>",
        );

    public function __construct(array $config = array()) {
        $keys = array('per_page', 'page', 'total_items', 'delta',
                      'page_parameter_name', 'base_url', 'template',
                      );
        foreach ($keys as $key) {
            if (@$config[$key]) {
                $this->$key = $config[$key];
            }
        }
    }

    public static function getOffset($page, $per_page) {
        return $per_page * ($page - 1);
    }

    public function render() {
        if ($this->total_items <= 0) {
            return '';
        }
        $start_page = $this->_getStartPage();

        $end_page   = $this->_getEndPage();

        $pagination = '';

        // previous page link
        if ($this->_isFirstPage()) {
            $pagination .= $this->_render('first-inactive');
            $pagination .= $this->_render('previous-inactive');
        } else {
            $pagination .= $this->_render('first', 1);
            $pagination .= $this->_render('previous', $this->_getCurrentPage() - 1);
        }

        // pages link
        for ($page = $start_page; $page <= $end_page; $page++) {
            if ($page == $this->_getCurrentPage()) { // current page
                $pagination .= $this->_render('active', $page);
            } else {
                $pagination .= $this->_render('regular', $page);
            }
        }

        // next page link
        if ($this->_isLastPage()) {
            $pagination .= $this->_render('next-inactive');
            $pagination .= $this->_render('last-inactive');
        } else {
            $pagination .= $this->_render('next', $this->_getCurrentPage() + 1);
            $pagination .= $this->_render('last', $this->_getTotalPages());
        }

        $html = $this->template['wrapper'];
        $html = str_replace('{pagination}', $pagination, $html);

        return $html;
    }

    private function _render($name, $page = null) {
        $uri = $this->_buildUrl($page);
        $html = $this->template[$name];
        $html = str_replace('{page}', $page, $html);
        $html = str_replace('{uri}', $uri, $html);
        return $html;
    }

    private function _isFirstPage() {
        if ($this->_getCurrentPage() == 1) {
            return true;
        }
        return false;
    }

    private function _isLastPage() {
        if ($this->_getCurrentPage() == $this->_getTotalPages()) {
            return true;
        }
        return false;
    }

    private function _buildUrl($page) {
        $base_url = $this->_getBaseUrl();
        $params = $_GET;
        $params[$this->page_parameter_name] = $page;
        $query = http_build_query($params);
        $url = $base_url. "?" . $query;
        return $url;
    }

    private function _getBaseUrl() {
        if ($this->base_url) {
            return $this->base_url;
        } else {
            $array = explode("?", @$_SERVER['REQUEST_URI']);
            $base_url = array_shift($array);
           return $base_url;
        }
    }

    private function _getStartPage() {
        $start_page = $this->_getCurrentPage() - $this->delta;
        if ($start_page < 1) {
            $start_page = 1;
        }
        return $start_page;
    }

    private function _getCurrentPage() {
        if ($this->page) {
            return $this->page;
        } elseif (@$_REQUEST[$this->page_parameter_name]) {
            return $_REQUEST[$this->page_parameter_name];
        } else {
            return 1;
        }
    }

    private function _getEndPage() {
        $end_page = $this->_getCurrentPage() + $this->delta;
        $total_pages = $this->_getTotalPages();
        if ($end_page > $total_pages) {
            $end_page = $total_pages;
        }
        return $end_page;
    }

    private function _getTotalPages() {
        return ceil($this->total_items / $this->per_page);
    }

}


