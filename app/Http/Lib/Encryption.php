<?php
namespace App\Http\Lib;

class Encryption
{
    public static function safe_b64encode($string)
    {
        $data = base64_encode($string);
        $data = str_replace(['+', '/', '='], ['-', '_', ''], $data);
        return $data;
    }

    public static function safe_b64decode($string)
    {
        $data = str_replace(['-', '_'], ['+', '/'], $string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }

    protected static function getKey()
    {
        $key = base64_decode(env('ENCKEY'));
        if (strlen($key) !== SODIUM_CRYPTO_SECRETBOX_KEYBYTES) {
            throw new \Exception('Invalid encryption key length');
        }
        return $key;
    }

    protected static function getNonce()
    {
        $nonce = base64_decode(env('NOUNCE'));
        if (strlen($nonce) !== SODIUM_CRYPTO_SECRETBOX_NONCEBYTES) {
            throw new \Exception('Invalid nonce length');
        }
        return $nonce;
    }

    public static function encode($value)
    {
        if (!$value) {
            return false;
        }
        $value = (string) $value;
        $ciphertext = sodium_crypto_secretbox($value, self::getNonce(), self::getKey());
        return trim(self::safe_b64encode($ciphertext));
    }

    public static function decode($value)
    {
        if (!$value) {
            return false;
        }
        $ciphertext = self::safe_b64decode($value);
        $decrypted = sodium_crypto_secretbox_open($ciphertext, self::getNonce(), self::getKey());
        if ($decrypted === false) {
            throw new \Exception('Decryption failed');
        }
        return trim($decrypted);
    }
}

