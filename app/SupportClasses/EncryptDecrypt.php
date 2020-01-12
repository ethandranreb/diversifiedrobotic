<?php

namespace App\SupportClasses;

use \Config;

class EncryptDecrypt
{
    static function encrypt($value)
    {
        $plaintext      = $value;
        $key            = Config::get('customconstants.options.salt');
        $cipher         = Config::get('customconstants.options.cipher');
        $ivlen          = openssl_cipher_iv_length($cipher);
        $iv             = openssl_random_pseudo_bytes($ivlen);
        $ciphertext_raw = openssl_encrypt($plaintext, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
        $hmac           = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);

        return base64_encode( $iv.$hmac.$ciphertext_raw );
    }

    static function decrypt($value)
    {
        $c              = base64_decode($value);
        $key            = Config::get('customconstants.options.salt');
        $cipher         = Config::get('customconstants.options.cipher');
        $ivlen          = openssl_cipher_iv_length($cipher);
        $iv             = substr($c, 0, $ivlen);
        $hmac           = substr($c, $ivlen, $sha2len=32);
        $ciphertext_raw = substr($c, $ivlen+$sha2len);
        $calcmac        = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);

        //PHP 5.6+ timing attack safe comparison
        if (hash_equals($hmac, $calcmac)): return openssl_decrypt($ciphertext_raw, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
        else: return '';
        endif;
    }
}