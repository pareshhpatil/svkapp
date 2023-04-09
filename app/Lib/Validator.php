<?php

/**
 *
 * - Fill out a form
 *    - POST to PHP
 *  - Sanitize
 *  - Validate
 *  - Return Data
 *  - Write to Database

 */
namespace App\Lib;
use App\Lib\ValidateValue;

class Validator {

    /** @var array $_currentItem The immediately posted item */
    protected $_currentItem = null;

    /** @var array $_postData Stores the Posted Data */
    protected $_postData = array();

    /** @var object $_val The validator object */
    public $_val;

    /** @var array $_error Holds the current forms errors */
    protected $_error = array();

    /**
     * __construct - Instantiates the validator class
     * 
     */
    public function __construct($model) {
        $this->_val = new ValidateValue($model);
    }

    /**
     * post - This is to run $_POST
     * 
     * @param string $field - The HTML fieldname to post
     */
    public function post($field, $field2_ = NULL) {
        if(isset($_POST[$field]))
        {
            
        }else
        {
            $_POST[$field]="";
        }
        $this->_postData[$field] = $_POST[$field];
        $this->_currentItem = $field;

        if ($field2_ != NULL)
            $this->_postData[$field2_] = $_POST[$field2_];

        return $this;
    }

    /**
     * Files - This is to run $_FILES
     * 
     * @param string $field - The HTML fieldname to FILES
     */
    public function file($field) {
        $this->_postData[$field] = $_FILES[$field];
        $this->_currentItem = $field;
        return $this;
    }

    /**
     * fetch - Return the posted data
     * 
     * @param mixed $fieldName
     * 
     * @return mixed String or array
     */
    public function fetch($fieldName = false) {
        if ($fieldName) {
            if (isset($this->_postData[$fieldName]))
                return $this->_postData[$fieldName];
            else
                return false;
        }
        else {
            return $this->_postData;
        }
    }

    /**
     * val - This is to validate
     * 
     * @param string $typeOfValidator A method from the Form/Val class
     * @param string $arg A property to validate against
     */
    public function val($typeOfValidator, $displayName_, $arg = null, $arg2 = null) {
        if ($arg == null && $arg2 == null)
            $error = $this->_val->{$typeOfValidator}($this->_postData[$this->_currentItem]);
        elseif ($arg != null && $arg2 == null)
            $error = $this->_val->{$typeOfValidator}($this->_postData[$this->_currentItem], $arg);
        else
            $error = $this->_val->{$typeOfValidator}($this->_postData[$this->_currentItem], $arg, $arg2);

        if ($error) {
            if (isset($this->_error[$this->_currentItem])) {
                $existingError = $this->_error[$this->_currentItem];
                $count = count($existingError);
                $existingError[$count] = $error;
                $this->_error[$this->_currentItem] = $existingError;
            } else {
                $existingError[0] = $displayName_;
                $existingError[1] = $error;
                $this->_error[$this->_currentItem] = $existingError;
            }
        }


        return $this;
    }

    /**
     * submit - Handles the form, and throws an exception upon error.
     * 
     * @return boolean
     * 
     * @throws Exception 
     */
    public function fetchErrors() {
        if (empty($this->_error)) {
            return false;
        } else {
            //$this->session = new Session();
            // $this->session->set('isValidPost', TRUE);
            return $this->_error;
        }
    }

}