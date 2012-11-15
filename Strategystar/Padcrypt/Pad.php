<?php

/**
 * This class can be used to pad strings with the following methods:
 * ANSI X.923, ISO 10126, PKCS5, PKCS7, Zero Padding, and Bit Padding
 * 
 * Note: PKCS5 is identical to PKCS7
 * 
 * The methods are implemented as documented at:
 * http://en.wikipedia.org/wiki/Padding_(cryptography)
 *
 * @author Strategy Star Inc.
 * @website http://www.strategystar.net
 */
class Strategystar_Padcrypt_Pad
{

    public static function padIso10126($data, $block_size)
    {
        $padding = $block_size - (strlen($data) % $block_size);

        for ($x = 1; $x < $padding; $x++)
        {
            mt_srand();
            $data .= chr(mt_rand(0, 255));
        }

        return $data . chr($padding);
    }

    public static function unpadIso10126($data)
    {
        $length = ord(substr($data, -1));
        return substr($data, 0, strlen($data) - $length);
    }

    public static function padAnsiX923($data, $block_size)
    {
        $padding = $block_size - (strlen($data) % $block_size);
        return $data . str_repeat(chr(0), $padding - 1) . chr($padding);
    }

    public static function unpadAnsiX923($data)
    {
        $length = ord(substr($data, -1));
        $padding_position = strlen($data) - $length;
        $padding = substr($data, $padding_position, -1);

        for ($x = 0; $x < $length; $x++)
        {
            if (ord(substr($padding, $x, 1)) != 0)
            {
                return $data;
            }
        }

        return substr($data, 0, $padding_position);
    }

    public static function padPkcs5($data, $block_size)
    {
        return padCrypt::pad_Pkcs7($data, $block_size);
    }

    public static function unpad_Pkcs5($data)
    {
        return padCrypt::unpad_Pkcs7($data);
    }

    public static function pad_Pkcs7($data, $block_size)
    {
        $padding = $block_size - (strlen($data) % $block_size);
        $pattern = chr($padding);
        return $data . str_repeat($pattern, $padding);
    }

    public static function unpad_Pkcs7($data)
    {
        $pattern = substr($data, -1);
        $length = ord($pattern);
        $padding = str_repeat($pattern, $length);
        $pattern_pos = strlen($data) - $length;

        if (substr($data, $pattern_pos) == $padding)
        {
            return substr($data, 0, $pattern_pos);
        }

        return $data;
    }

    public static function padBit($data, $block_size)
    {
        $length = $block_size - (strlen($data) % $block_size) - 1;
        return $data . "\x80" . str_repeat("\x00", $length);
    }

    public static function unpadBit($data)
    {
        if (substr(rtrim($data, "\x00"), -1) == "\x80")
        {
            return substr(rtrim($data, "\x00"), 0, -1);
        }

        return $data;
    }

    public static function padZero($data, $block_size)
    {
        $length = $block_size - (strlen($data) % $block_size);
        return $data . str_repeat("\x00", $length);
    }

    public static function unpadZero($data)
    {
        return rtrim($data, "\x00");
    }

}
