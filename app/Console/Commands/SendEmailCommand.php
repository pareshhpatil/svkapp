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
        $ccEmails = [];
        // $ccEmails = [
        //     'cc1@example.com' => 'CC Recipient 1',
        //     'cc2@example.com' => 'CC Recipient 2',
        //     // Add more CC recipients as needed
        // ];

        Mail::to($toEmail, $toName)->cc($ccEmails)->send(new SampleEmail('Hii', 54));

        $this->info('Email sent successfully!');
    }
}
