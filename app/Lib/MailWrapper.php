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

use Mail;
use Exception;
use Log;

class MailWrapper {

    protected $email = null;
    protected $subject = NULL;

    public function sendmail($to_email, $file, $data, $subject) {
        try {
            Log::debug('Email sending.. to ' . $to_email);
            $this->email = $to_email;
            $this->subject = $subject;
            Mail::send('emails.' . $file, $data, function($message) {
                        $message->from('info@globalinsurance.co.in', 'Global');
                        $message->to($this->email);
                        $message->subject($this->subject);
                    });
            Log::info('Email sent.');
        } catch (Exception $e) {
            Log::info('Email sending failed. Response: ' . $e->getMessage());
        }
    }

}