<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\SampleEmail; // Replace with your Mailable class
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingEmail; // Replace with your Mailable class
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

        $data['booking_id'] = '';
        $data['pickup_time'] = '';
        $data['pickup_address'] = '';
        $data['driver_name'] = '';
        $data['driver_mobile'] = '';
        $data['vehicle_number'] = '';
        $data['vehicle_type'] = '';
        $data['passengers'] = [];

 #       Mail::to($toEmail, $toName)->cc($ccEmails)->send(new BookingEmail('Thanks! Your cab booking is confirmed', $data));
  #      die();



        $toEmail = 'pareshhpatil@gmail.com';
        $toName = 'Paresh';
        $ccEmails = ['paresh@swipez.in'];
        // $ccEmails = [
        //     'cc1@example.com' => 'CC Recipient 1',
        //     'cc2@example.com' => 'CC Recipient 2',
        //     // Add more CC recipients as needed
        // ];

        // Mail::to($toEmail, $toName)->cc($ccEmails)->send(new SampleEmail('Hii', 54));
        // $body = view('emails.sample', []);
        $body = View::make('emails.sample', [])->render();
        //$body = htmlspecialchars($body, ENT_QUOTES);
        //echo $body;
        $this->sendemail($toEmail, $ccEmails, 'Hi', $body);

        $this->info('Email sent successfully!');
    }


    public function sendemail($to_email, $ccEmails, $subject, $body)
    {
        $cc_array = [];
        foreach ($ccEmails as $k => $email) {
            $cc_array[$k]['email'] = $email;
        }
        $curl = curl_init();
        $json = '{
            "sender":{
               "name":"'.env('MAIL_FROM_NAME').'",
               "email":"'.env('MAIL_FROM_ADDRESS').'"
            },
            "to":[
               {
                  "email":"' . $to_email . '"
               }
            ],
            "cc":' . json_encode($cc_array) . ',
            "subject":"' . $subject . '",
            "htmlContent":""
         }';

        $array = json_decode($json, 1);
        $array['htmlContent'] = $body;
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.brevo.com/v3/smtp/email',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($array),
            CURLOPT_HTTPHEADER => array(
                'accept: application/json',
                'api-key: ' . env('EMAIL_KEY'),
                'content-type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        echo $response;
        curl_close($curl);
    }
}
