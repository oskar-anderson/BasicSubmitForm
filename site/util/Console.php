<?php

namespace App\util;

use DateTimeImmutable;
use DateTimeZone;

class Console
{
   public static string $Break = "<br>";
   public static string $Linefeed = "\n";
   public static string $BreakLF = "<br>\n";

   public function __construct(
      private string $newline,
      private bool $withDate
   ) {
   }


    /**
     * Wrap the echo function for better syntax and automatic newline support
     * @param string $value Message that will be echoed
    *  @return void
    */
    public function writeLine(string $value = ""): void
    {
        echo ($this->withDate && $value !== "" ? ((static::GetDateNowAsDateTime())->format("H:i:s:u") . " ") : "") .
           $value . $this->newline;
    }

   public static function GetDateNowAsDateTime(): DateTimeImmutable
   {
      $datetime = new DateTimeImmutable();
      $timezone = new DateTimeZone('Europe/Tallinn');
      $datetime->setTimezone($timezone);
      return $datetime;
   }
}