<?php

namespace App\Http\Lib;

use Illuminate\Support\Facades\Http;

class GoogleTranslateService
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.google_translate.api_key'); // Load API key from config
    }

    public function translate($text, $targetLanguage)
    {

        $response = Http::post('https://translation.googleapis.com/language/translate/v2', [
            'query' => [
                'key' => $this->apiKey,
                'q' => $text,
                'target' => $targetLanguage,
            ],
        ]);
        dd($response);

        $data = json_decode($response->getBody()->getContents(), true);

        if (isset($data['data']['translations'][0]['translatedText'])) {
            return $data['data']['translations'][0]['translatedText'];
        }

        return null; // Translation not found or error handling
    }
}
