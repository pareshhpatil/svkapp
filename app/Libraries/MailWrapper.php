<?php

namespace App\Libraries;

use Mail;
use Exception;
use Log;

class MailWrapper
{

    public $email = null;
    public $subject = NULL;
    public $attachment = NULL;
    public $attachment_name = NULL;
    public $attachment_type = NULL;
    public $cc = array();
    public $multi = array();
    public $from_email = NULL;
    public $from_name = NULL;

    public function sendmail($to_email, $file, $data, $subject)
    {
        try {
            Log::debug('Email sending.. to ' . $to_email);
            $this->email = $to_email;
            $this->subject = $subject;
            if (isset($data['multiattach'])) {
                $this->multi = $data['multiattach'];
               
               
            }
            else if (isset($data['attachment'])) {
                $this->attachment = $data['attachment'];
                $this->attachment_name = $data['attachment_name'];
                if (isset($data['attachment_type'])) {
                    $this->attachment_type = $data['attachment_type'];
                }
            }
            Mail::send('mailer.' . $file, $data, function ($message) {
                $from_email = ($this->from_email == null) ? env('SENDER_EMAIL') : $this->from_email;
                $from_name = ($this->from_name == null) ? env('MAILER_SENDER_NAME') : $this->from_name;
                $message->from($from_email, $from_name);
                $message->to($this->email);
                if (!empty($this->cc)) {
                    $message->cc($this->cc);
                }
                $message->subject($this->subject);
                
                if ($this->multi) {
                    foreach ($this->multi as $files) {
                      
               

                        $message->attach($files['path'], [
                            'as' => $files['name'],
                            'mime' => 'application/pdf'
                        ]);
                    
                }
                }else if ($this->attachment != null) {

                    if ($this->attachment_type == null) {

                        $message->attach($this->attachment, [
                            'as' => $this->attachment_name,
                            'mime' => 'application/pdf'
                        ]);
                    } else {
                        $message->attach($this->attachment, [
                            'as' => $this->attachment_name,
                            'mime' => $this->attachment_type
                        ]);
                    }
                }
            });
            if (Mail::failures()) {
                Log::error('Email sending failed.');
            } else {
                Log::info('Email sent.');
            }
        } catch (Exception $e) {
            Log::error('Email sending failed. Response: ' . $e->getMessage());
        }
    }
}
