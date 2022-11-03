<?php

include('../config.php');
$ab = new Bulkemail();

class Bulkemail {

    public $db = NULL;
    public $logger = NULL;

    function __construct() {
        $this->db = new DBWrapper();
        $this->logger = new SwipezLogger('log4php_config.xml');
        $this->sending();
    }

    function sending() {

        $file = fopen("renew.csv", "r");
        while (!feof($file)) {
            $mem_ = fgetcsv($file);
            //if ($mem_[5] == 'Basic Plan Renewal') {
            //    $this->logger->debug(__CLASS__, 'Sending email initiate Email : ' . $mem_[2]);
            //    $this->sendMail($mem_[2], $mem_[6]);
            //}
            if ($mem_[5] == 'Website Builder Renewal') {
                $this->logger->debug(__CLASS__, 'Sending email initiate Email : ' . $mem_[2]);
                $this->sendMail($mem_[2], $mem_[6]);
            }
        }
    }

    function sendMail($toEmail_, $short_link) {
        try {
            $emailWrapper = new EmailWrapper();
            $message = @file_get_contents("bulkemail2.html");
            $message = str_replace('__URL__', $short_link, $message);
            $subject = 'Keep your website running';
            //$subject = 'Renew your Swipez subscription';
            $emailWrapper->from_email_ = 'accounts@swipez.in';
            $emailWrapper->sendMail($toEmail_, "", $subject, $message);
        } catch (Exception $e) {
Sentry\captureException($e);
            
$this->logger->error(__CLASS__, '[BE106]Error while sending mail Error: ' . $e->getMessage());
        }
    }

}
