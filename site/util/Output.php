<?php

namespace App\util;

class Output
{
   /** @noinspection PhpUnusedParameterInspection */
   public static function Render($fileName, $model): void
   {
      require($fileName);
   }
}