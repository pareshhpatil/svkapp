<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StaffModel;
use Validator;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as Req;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

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

        $data = $request->all();
        $model = $this->model;
        if (isset($data['entry'][0]['changes'][0]['value']['contacts'][0]['profile']['name'])) {
            $name = $data['entry'][0]['changes'][0]['value']['contacts'][0]['profile']['name'];
            $mobile = $data['entry'][0]['changes'][0]['value']['messages'][0]['from'];
            $message_type = $data['entry'][0]['changes'][0]['value']['messages'][0]['type'];
            $message_id = $data['entry'][0]['changes'][0]['value']['messages'][0]['id'];
            if ($message_type == 'text') {
                $message = $data['entry'][0]['changes'][0]['value']['messages'][0]['text']['body'];
            } else if ($message_type == 'image') {
                $image_id = $data['entry'][0]['changes'][0]['value']['messages'][0]['image']['id'];
                $message = $this->getWhatsappImage($image_id);
            }
            dd($message);
            $model->saveWhatsapp(substr($mobile, 2), $name, 'Received', 'delivered', $message_type, $message, $message_id);
        }
        if (isset($data['entry'][0]['changes'][0]['value']['statuses'][0]['id'])) {
            $message_id = $data['entry'][0]['changes'][0]['value']['statuses'][0]['id'];
            $status = $data['entry'][0]['changes'][0]['value']['statuses'][0]['status'];
            $timestamp = $data['entry'][0]['changes'][0]['value']['statuses'][0]['timestamp'];

            $model->updateWhatsappStatus($message_id, $status, $timestamp);
        }




        Log::error('Facebook Webhook: ' . json_encode($request->all()));

        echo $request->hub_challenge;
    }



    function getWhatsappImage($image_id)
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
            $fileName = "media_file_{$timestamp}.jpg";
            $filePath = "{$directoryPath}/{$fileName}";

            Storage::disk('local')->put($filePath, $response->body());
            $url = Storage::url($filePath);
            return $url;

            // Optionally, do something with the saved file path
        }
    }
}
