<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\SampleEmail; // Replace with your Mailable class

class SendEmailCommand extends Command
{
    protected $signature = 'email:send';
    protected $description = 'Send sample email from command';

    public function handle()
    {
        $toEmail = 'pareshhpatil@gmail.com';
        $toName = 'Paresh';

        Mail::to($toEmail, $toName)->send(new SampleEmail());

        $this->info('Email sent successfully!');
    }
}

