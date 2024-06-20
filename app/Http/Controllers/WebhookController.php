<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StaffModel;
use Validator;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as Req;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Log;
use App\Http\Controllers\ApiController;
use App\Http\Lib\Encryption;

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
        Log::info('Facebook Webhook: ' . json_encode($request->all()));

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
            $model->saveWhatsapp(substr($mobile, 2), $name, 'Received', 'delivered', $message_type, $message, $message_id);
            $apiController = new ApiController();
            $url = 'https://app.svktrv.in/whatsapp/' . Encryption::encode(substr($mobile, 2));
            $message = 'Whatsapp from ' . $name;
            //$description = 'Message received from ' . $name;
            $apiController->sendNotification(1, 1, $message, $description, $url, $image);
        }
        if (isset($data['entry'][0]['changes'][0]['value']['statuses'][0]['id'])) {
            $message_id = $data['entry'][0]['changes'][0]['value']['statuses'][0]['id'];
            $status = $data['entry'][0]['changes'][0]['value']['statuses'][0]['status'];
            $timestamp = $data['entry'][0]['changes'][0]['value']['statuses'][0]['timestamp'];
            $error_code = '';
            if (isset($data['entry'][0]['changes'][0]['value']['statuses'][0]['errors'][0]['code'])) {
                $error_code = $data['entry'][0]['changes'][0]['value']['statuses'][0]['errors'][0]['code'];
            }

            $model->updateWhatsappStatus($message_id, $status, $timestamp);
            if ($error_code == '131026') {
                $model->saveWhatsappFailed(substr($mobile, 2));
            }
        }




        if (isset($request->hub_challenge)) {
            echo $request->hub_challenge;
        }
    }



    function getWhatsappImage($image_id, $extension = 'jpg')
    {
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
}
