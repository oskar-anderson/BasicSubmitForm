<?php

namespace App\util;

require_once (__DIR__."/../../vendor/autoload.php");


class Base64
{
   public static function GenerateId(int $length = 22): string
   {
      $byteToBase64RatioLength = intval(ceil($length * 3 / 4));
      $guid = base64_encode(random_bytes($byteToBase64RatioLength));
      $guid = str_replace(['+', '/'], ['-', '_'], $guid);  // make url friendly
      return substr($guid, 0, $length); // remove last 0-3 chars if length is not 4 divisible
   }

}