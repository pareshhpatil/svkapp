<?php

/**
 * Login model works for patron and merchant login
 *
 * @author Paresh
 */
class HelpdeskModel extends Model
{

    function __construct()
    {
        parent::__construct();
    }

    

    public function fetchMessage($type)
    {
        return $this->SMSMessage->fetch($type);
    }
}
