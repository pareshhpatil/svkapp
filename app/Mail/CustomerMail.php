<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomerMail extends Mailable
{
    use Queueable, SerializesModels;
    public $useremail, $username,$userquery;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($useremail, $username,$userquery)
    {
        $this->useremail = $useremail;
        $this->username = $username;
        $this->userquery = $userquery;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.from.address'),'Swipez')->view('mpages.customer_mail');
    }
}
