<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubUserWelcome extends Mailable
{
    use Queueable, SerializesModels;

    /***
     * @param $verifyUrl
     */
    public $verifyUrl;

    /**
     * Create a new message instance.
     *
     * @param $verifyUrl
     */
    public function __construct($token)
    {
        $this->verifyUrl = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject('Please verify your email address with Swipez')
            ->markdown('emails.subuser.welcome');

        return $this;
    }
}
