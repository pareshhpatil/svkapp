<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StaffModel;
use Validator;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as Req;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\ApiController;
use App\Http\Lib\Encryption;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingEmail; // Replace with your Mailable class
use App\Http\Controllers\TripController;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $model;
    public function __construct()
    {
        $this->model = new StaffModel();
    }


    public function facebookWebhook(Request $request)

    {
        Log::error('Facebook Webhook: ' . json_encode($request->all()));

        $data = $request->all();
        $model = $this->model;
        if (isset($data['entry'][0]['changes'][0]['value']['contacts'][0]['profile']['name'])) {
            $image = '';
            $name = $data['entry'][0]['changes'][0]['value']['contacts'][0]['profile']['name'];
            $mobile = $data['entry'][0]['changes'][0]['value']['messages'][0]['from'];
            $message_type = $data['entry'][0]['changes'][0]['value']['messages'][0]['type'];
            $message_id = $data['entry'][0]['changes'][0]['value']['messages'][0]['id'];
            if ($message_type == 'text') {
                $message = $data['entry'][0]['changes'][0]['value']['messages'][0]['text']['body'];
                $description = $message;
            } else if ($message_type == 'image') {
                $image_id = $data['entry'][0]['changes'][0]['value']['messages'][0]['image']['id'];
                $message = $this->getWhatsappImage($image_id);
                $image = $message;
                $description = 'Image';
            } else if ($message_type == 'reaction') {
                $message_id = $data['entry'][0]['changes'][0]['value']['messages'][0]['reaction']['message_id'];
                $emoji = $data['entry'][0]['changes'][0]['value']['messages'][0]['reaction']['emoji'];
                $model->updateTable('whatsapp_messages', 'message_id', $message_id, 'reaction', $emoji);
                $message = $emoji;
                $description = $emoji;
            } else if ($message_type == 'document') {
                $image_id = $data['entry'][0]['changes'][0]['value']['messages'][0]['document']['id'];
                $filename = $data['entry'][0]['changes'][0]['value']['messages'][0]['document']['filename'];
                $extension = pathinfo($filename, PATHINFO_EXTENSION);
                $message = $this->getWhatsappImage($image_id, $extension);
                $image = $message;
                $description = 'Document';
            } else if ($message_type == 'audio') {
                $image_id = $data['entry'][0]['changes'][0]['value']['messages'][0]['audio']['id'];
                $message = $this->getWhatsappImage($image_id, 'ogg');
                $image = $message;
                $description = 'Audio';
            } else if ($message_type == 'video') {
                $image_id = $data['entry'][0]['changes'][0]['value']['messages'][0]['video']['id'];
                $message = $this->getWhatsappImage($image_id, 'mp4');
                $image = $message;
                $description = 'Video';
            } else if ($message_type == 'location') {
                $latitude = $data['entry'][0]['changes'][0]['value']['messages'][0]['location']['latitude'];
                $longitude = $data['entry'][0]['changes'][0]['value']['messages'][0]['location']['longitude'];
                $message = 'https://www.google.com/maps/search/?api=1&query=' . $latitude . ',' . $longitude;
                $description = 'Location received';
            } else if ($message_type == 'contacts') {
                $contact = $data['entry'][0]['changes'][0]['value']['messages'][0]['contacts'][0];
                $message = $contact['name']['formatted_name'] . ' <br>';
                if ($contact['phones'][0]['phone']) {
                    $message .= " <br> Phone: " . $contact['phones'][0]['phone'];
                }
                if ($contact['phones'][0]['wa_id']) {
                    $message .= " <br> Whatsapp: " . substr($contact['phones'][0]['wa_id'], 2);
                }
                $description = 'Contact received';
            }

            if (isset($data['entry'][0]['changes'][0]['value']['messages'][0]['context'])) {
                $message_id = $data['entry'][0]['changes'][0]['value']['messages'][0]['context']['id'];
                $model->updateTable('whatsapp_messages', 'message_id', $message_id, 'reaction', $message);
                $message_type = 'reaction';
            }
            if ($message_type != 'reaction') {
                $exist = $model->getColumnValue('whatsapp_messages', 'message_id', $message_id, 'message_id');
                if ($exist == false) {
                    $model->saveWhatsapp(substr($mobile, 2), $name, 'Received', 'delivered', $message_type, $message, $message_id);
                }
            }
            $apiController = new ApiController();
            $url = 'https://app.svktrv.in/whatsapp/' . Encryption::encode(substr($mobile, 2));
            $message = 'Whatsapp from ' . $name;
            //$description = 'Message received from ' . $name;
            $apiController->sendNotification(1, 1, $message, $description, $url, $image);
        }
        if (isset($data['entry'][0]['changes'][0]['value']['statuses'][0]['id'])) {
            //if (isset($data['entry'][0]['changes'][0]['value']['messages'][0]['from'])) {
            $message_id = $data['entry'][0]['changes'][0]['value']['statuses'][0]['id'];
            $status = $data['entry'][0]['changes'][0]['value']['statuses'][0]['status'];
            $timestamp = $data['entry'][0]['changes'][0]['value']['statuses'][0]['timestamp'];
            $error_code = '';
            if (isset($data['entry'][0]['changes'][0]['value']['statuses'][0]['errors'][0]['code'])) {
                $error_code = $data['entry'][0]['changes'][0]['value']['statuses'][0]['errors'][0]['code'];
            }

            $model->updateWhatsappStatus($message_id, $status, $timestamp);
            if ($error_code == '131026') {
                $mobile = $data['entry'][0]['changes'][0]['value']['messages'][0]['from'];
                $model->saveWhatsappFailed(substr($mobile, 2));
            }
            //  }
        }




        if (isset($request->hub_challenge)) {
            echo $request->hub_challenge;
        }
    }


    function downloadWhatsappMediaStream($image_id)
    {
        $accessToken = env('WHATSAPP_TOKEN');

        // Step 1: Fetch media metadata
        $mediaMetaResponse = Http::withToken($accessToken)
            ->get("https://graph.facebook.com/v20.0/{$image_id}");

        if (!$mediaMetaResponse->successful()) {
            throw new \Exception('Failed to fetch media metadata.');
        }

        $mediaUrl = $mediaMetaResponse->json('url');

        if (!$mediaUrl) {
            throw new \Exception('Media URL not found in the response.');
        }

        // Step 2: Get the media file as a stream
        $mediaFileResponse = Http::withToken($accessToken)
            ->withOptions(['stream' => true])
            ->get($mediaUrl);

        if (!$mediaFileResponse->successful()) {
            throw new \Exception('Failed to download media file.');
        }

        // Step 3: Guess file extension
        $contentType = $mediaFileResponse->header('Content-Type');

        $extension = match ($contentType) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'video/mp4' => 'mp4',
            'application/pdf' => 'pdf',
            'audio/ogg' => 'ogg',
            default => 'bin',
        };

        // Step 4: Generate file path
        $timestamp = time();
        $fileName = "media_file_{$timestamp}.{$extension}";
        $filePath = "whatsapp/{$fileName}";

        // Step 5: Stream upload to S3
        $stream = $mediaFileResponse->toPsrResponse()->getBody();

        Storage::disk('s3')->put($filePath, $stream);

        return Storage::disk('s3')->url($filePath);
    }


    function getWhatsappImage($image_id, $extension = 'jpg')
    {
        return $this->getWhatsappImagenew($image_id, $extension = 'jpg');
        $accessToken = env('WHATSAPP_TOKEN');

        // Make a GET request to retrieve media URL
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->get('https://graph.facebook.com/v19.0/' . $image_id);

        // Get media URL from response
        $mediaUrl = $response->json('url');
        // Make a GET request to fetch media file with streaming response
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken
        ])->get($mediaUrl);
        // Save the media file to storage
        if ($response->successful()) {
            $timestamp = time();
            $directoryPath = 'public/media';
            $fileName = "media_file_{$timestamp}." . $extension;
            $filePath = "{$directoryPath}/{$fileName}";

            Storage::disk('local')->put($filePath, $response->body());
            $url = Storage::url($filePath);
            return env('APP_URL') . $url;

            // Optionally, do something with the saved file path
        }
    }

    function getWhatsappImagenew($image_id, $extension = 'jpg')
    {
        return $this->downloadWhatsappMediaStream($image_id);
        $accessToken = env('WHATSAPP_TOKEN');

        // Make a GET request to retrieve media URL
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->get('https://graph.facebook.com/v20.0/' . $image_id);

        // Get media URL from response
        $mediaUrl = $response->json('url');
        // Make a GET request to fetch media file with streaming response
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken
        ])->get($mediaUrl);
        // Save the media file to storage
        if ($response->successful()) {
            $timestamp = time();
            $directoryPath = 'public/media';
            $fileName = "media_file_{$timestamp}." . $extension;
            $filePath = "{$directoryPath}/{$fileName}";

            $file_name = 'whatsapp/' . $fileName;
            Storage::disk('s3')->put($file_name, $response->body());
            $path = Storage::disk('s3')->url($file_name);
            return $path;

            // Optionally, do something with the saved file path
        }
    }


    function notificationBooking($trip_id)
    {
        $trip = $this->model->getTableRow('trip', 'trip_id', $trip_id);
        $ride = $this->model->getTableRow('ride', 'id', $trip->ride_id);

        $data['driver_id'] = $ride->driver_id;
        $data['vehicle_id'] = $ride->vehicle_id;
        $data['escort'] = $ride->escort;
        $data['ride_id'] = $trip->ride_id;
        $data['pickup_location'] = $trip->pickup_location;
        if ($trip->emails == '') {
            $trip->emails = 'maheshspatil.88@gmail.com';
        }
        if ($trip->mobiles == '') {
            $trip->mobiles = '8879391658';
        }
        $data['emails'] = explode(',', $trip->emails);
        $data['mobiles'] = explode(',', $trip->mobiles);
        $data['passengers'] = explode(',', $trip->passengers);
        $tripController = new TripController();
        $request = new Request($data);
        $tripController->assignCab($request);


        // $toEmail = 'pareshhpatil@gmail.com';
        // $toName = 'Paresh';
        // $ccEmails = ['cc1@example.com', 'cc1@example.com'];
        // // $ccEmails = [
        // //     'cc1@example.com' => 'CC Recipient 1',
        // //     'cc2@example.com' => 'CC Recipient 2',
        // //     // Add more CC recipients as needed
        // // ];

        // $data['booking_id'] = '';
        // $data['pickup_time'] = '';
        // $data['pickup_address'] = '';
        // $data['driver_name'] = '';
        // $data['driver_mobile'] = '';
        // $data['vehicle_number'] = '';
        // $data['vehicle_type'] = '';
        // $data['passengers'] = [];

        // Mail::to($toEmail, $toName)->cc($ccEmails)->send(new BookingEmail('Thanks! Your cab booking is confirmed', $data));
    }
}
