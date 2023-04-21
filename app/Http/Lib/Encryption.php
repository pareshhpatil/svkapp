<?php
namespace App\Http\Lib;
 class Encryption {

    public static function safe_b64encode($string) {
        $data = base64_encode($string);
        $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
        return $data;
    }

    public static function safe_b64decode($string) {
        $data = str_replace(array('-', '_'), array('+', '/'), $string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }

    public static function encode($value) {
        if (!$value) {
            return false;
        }
        $value=(string)$value;
        $ciphertext = sodium_crypto_secretbox($value, env('NOUNCE'), env('ENCKEY'));
        return trim(self::safe_b64encode($ciphertext));
    }

    public static function decode($value) {
        if (!$value) {
            return false;
        }
        $ciphertext = self::safe_b64decode($value);
        $decrypttext = sodium_crypto_secretbox_open($ciphertext, env('NOUNCE'), env('ENCKEY'));
        return trim($decrypttext);
    }

}
