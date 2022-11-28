<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of helpdeskMessage
 *
 * @author AJ
 */
class HelpdeskMessage {
    private $_messageList = array();
                 
    function __construct() {
        // when not logged-in
        $this->_messageList["c1"]=array('Name'=>"",'Subject'=>"",'Body'=>"");
        
        // when logged in
        $this->_messageList["c2"]=array('Name'=>"",'Subject'=>"",'Body'=>"");
        
        // when the merchant clicks the On Request button for Enterprise Package selection when loggedin
        $this->_messageList["c3"]=array('Name'=>"Pricing plan - Enterprise On Request (logged in merchant)",'Subject'=>"Enterprise setup query",'Body'=>"Business need summary:\n
                                 Estimated transaction volume:\n
                                 Alternate contact number:\n
                                 Alternate contact email id:\n");
                
        // when the merchant clicks the On Request button for Enterprise Package selection when not loggedin
        $this->_messageList["c4"]=array('Name'=>"Pricing plan - Enterprise On Request (non-logged in merchant)",'Subject'=>"Enterprise setup query",'Body'=>"Company name:\n
                                   Business need summary:\n
                                   Estimated transaction volume:\n
                                   Contact numbers:\n
                                   Email ID:");
                
        // for legal paperwork completion
        $this->_messageList["c5"]=array('Name'=>"",'Subject'=>"",'Body'=>"");
        
        // which package to select
        $this->_messageList["c6"]=array('Name'=>"",'Subject'=>"",'Body'=>"");
        
    }
    
    function fetch($key_) {
        $value = isset($this->_messageList[$key_])? $this->_messageList[$key_] : NULL;
        return $value;
    }
}



?>
