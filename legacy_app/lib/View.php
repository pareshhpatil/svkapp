<?php

class View {

    protected $_error = NULL;
    public $canonical = '';

    function __construct() {
        //echo 'this is the view';
    }

    public function render($name, $noInclude = false) {
        require VIEW . $name . '.php';
    }

    public function setError($error_) {
        $this->_error = $error_;
    }

    public function hasError() {
        if (is_array($this->_error)) {
            return TRUE;
        }
    }

    public function fileTime($type, $file) {
        if ($type == 'js') {
            return '?version=' . filemtime('assets/admin/layout/scripts/' . $file . '.js');
        } elseif ($type == 'css') {
            return '?version=' . filemtime('assets/admin/layout/css/' . $file . '.css');
        } else {
            return '?version=' . filemtime($file);
        }
    }

}
