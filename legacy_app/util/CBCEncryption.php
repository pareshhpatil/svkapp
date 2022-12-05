<?php

include("AES.class.php");

class CBCEncryption {

    private $skey = "bcb04b7e103a0cd8b54763051cef096f"; // you can change it
    private $iv = '1234567890abcdef';
    private $mode = 'CBC';

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

    public function encode($text) {
        if (!$text) {
            return false;
        }
        $text = $this->pkcs5_pad($text);
        $aes = new Aes($this->skey, $this->mode, $this->iv);
        $encrypted = $aes->encrypt($text);
        return trim($this->safe_b64encode($encrypted));
    }

    public function decode($value) {
        if (!$value) {
            return false;
        }
        $aes = new Aes($this->skey, $this->mode, $this->iv);
        $decrypttext = $this->safe_b64decode($value);
        $decrypted = $aes->decrypt($decrypttext);
        $unpad_text = $this->pkcs5_unpad($decrypted);
        return trim($unpad_text);
    }

    function pkcs5_pad($text) {
        $blocksize = 16;
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    function pkcs5_unpad($text) {
        $pad = ord($text{strlen($text) - 1});
        if ($pad > strlen($text))
            return false;
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad)
            return false;
        return substr($text, 0, -1 * $pad);
    }

}
