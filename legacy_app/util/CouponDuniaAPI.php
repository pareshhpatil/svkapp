<?php

/**
 * Version 1.0
 */
class CouponDuniaAPI {

    private $client_id = null;
    private $client_secret_key = null;
    private $timestamp = null;

    function __construct() {
        $this->client_id = 167;
        $this->client_secret_key = '4613D7B9-AA55-156D-D3C4-6DF65C1966AE';
        $this->timestamp = time();
    }

    private function getChecksum($query) {
        $q = ($query != '') ? 'q=' . $query : '';
        $string = $this->client_id . $this->timestamp . $this->client_secret_key . $q;
        $md5 = md5($string) . ':';
        $checksum = base64_encode($md5);
        return $checksum;
    }

    public function getOffer($query) {
        $url = 'https://api.coupondunia.in/search?q=' . $query;
        $checksum = $this->getChecksum($query);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => '',
            CURLOPT_HTTPHEADER => array(
                "Authorization:Basic  " . $checksum,
                "X-Requested-By:" . $this->client_id,
                "X-Request-Timestamp:" . $this->timestamp
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        return $response;
    }

    public function getCode($id) {
        $url = 'https://api.coupondunia.in/offer/' . $id . '/code';
        $checksum = $this->getChecksum('');
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => '',
            CURLOPT_HTTPHEADER => array(
                "Authorization:Basic  " . $checksum,
                "X-Requested-By:" . $this->client_id,
                "X-Request-Timestamp:" . $this->timestamp
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        return $response;
    }

    public function bestOffers() {
        $url = 'https://api.coupondunia.in/best-offers';
        $checksum = $this->getChecksum('');
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => '',
            CURLOPT_HTTPHEADER => array(
                "Authorization:Basic  " . $checksum,
                "X-Requested-By:" . $this->client_id,
                "X-Request-Timestamp:" . $this->timestamp
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        return $response;
    }

    public function stores() {
        $url = 'https://api.coupondunia.in/stores';
        $checksum = $this->getChecksum('');
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => '',
            CURLOPT_HTTPHEADER => array(
                "Authorization:Basic  " . $checksum,
                "X-Requested-By:" . $this->client_id,
                "X-Request-Timestamp:" . $this->timestamp
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        return $response;
    }

    public function storeOffers($id) {
        $url = 'https://api.coupondunia.in/stores/' . $id . '/offers';
        $checksum = $this->getChecksum('');
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => '',
            CURLOPT_HTTPHEADER => array(
                "Authorization:Basic  " . $checksum,
                "X-Requested-By:" . $this->client_id,
                "X-Request-Timestamp:" . $this->timestamp
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        return $response;
    }

}
