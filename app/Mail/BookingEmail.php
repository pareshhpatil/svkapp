<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    public $subject;
    public function __construct($subject, $data)
    {
        $this->data = $data;
        $this->subject = $subject;
    }

    public function build()
    {
        return $this->subject($this->subject)
            ->view('emails.booking', $this->data); // Blade view for email content
    }
}
