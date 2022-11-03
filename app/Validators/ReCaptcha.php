<?php

namespace App\Validators;

use GuzzleHttp\Client;
use Log;

class ReCaptcha {

    public function validate($attribute, $value, $parameters, $validator) {
        if (isset($_POST['recaptcha_response']) && !empty($_POST['recaptcha_response'])) {
            //your site secret key
            $secret = env('V3_CAPTCHA_SECRET');
            //get verify response data
            $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secret) . '&response=' . urlencode($_POST['recaptcha_response']);
            $response = file_get_contents($url);
            $responseKeys = json_decode($response, true);
            header('Content-type: application/json');
            if ($responseKeys["success"]) {
                if ($responseKeys["score"] >= 0.05) {
                    return true;
                }
            }
        }
        return false;
    }

    public function messages() {
        return [
            'recaptcha' => 'Please ensure that you are a human!',
            
        ];
    }

}
