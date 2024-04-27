<?php
namespace App\Lib;
class Encryption {
    // Generating an encryption key and a nonce
    var $key = 'fce42da6c88468b47c571c4b5e447bdf'; // 256 bit
    var $nonce = '4f7be50f3d256c6104ffe3f4'; // 24 bytes

    public function safe_b64encode($string) {
        $data = base64_encode($string);
        $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
        return $data;
    }

    public function safe_b64decode($string) {
        $data = str_replace(array('-', '_'), array('+', '/'), $string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }

    public function encode($value) {
        return $value;
        if (!$value) {
            return false;
        }
        $value=(string)$value;
        $ciphertext = sodium_crypto_secretbox($value, $this->nonce, $this->key);
        return trim($this->safe_b64encode($ciphertext));
    }

    public function decode($value) {
        return $value;
        if (!$value) {
            return false;
        }
        $ciphertext = $this->safe_b64decode($value);
        $decrypttext = sodium_crypto_secretbox_open($ciphertext, $this->nonce, $this->key);
        return trim($decrypttext);
    }

}


?>