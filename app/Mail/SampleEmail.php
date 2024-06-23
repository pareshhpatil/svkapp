<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SampleEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    public function __construct($subject, $data)
    {
        $this->data = $data;
    }

    public function build()
    {
        return $this->subject('Sample Email Subject')
            ->view('emails.sample', $this->data); // Blade view for email content
    }
}
