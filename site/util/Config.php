<?php


namespace App\util;

require_once(__DIR__."/../../vendor/autoload.php");
use Dotenv\Dotenv;
use Exception;

class Config
{
   public function __construct()
   {
      $environmentFileExists = file_exists(__DIR__.'/../../.env');
      // .env file is used for local development,
      // hosting platforms have other methods for setting up Config variables
      if ($environmentFileExists) {
         $dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
         $dotenv->load();
      }
   }


   /**
    * @throws Exception Undefined
    */
   private function GetByName(string $name): string {
      if ($_ENV[$name] === null || $_ENV[$name] === "") {
         throw new Exception("Internal error! Environment config variable $name not defined.");
      }
      return $_ENV[$name];
   }

   /**
    * @throws Exception Undefined
    */
   public function getDatabaseName(): string
   {
      return $this->GetByName("dbName");
   }

   /**
    * @throws Exception Undefined
    */
   public function GetConnectDsn(): string
   {
      return $this->GetByName("dbConnectDsn");
   }

   /**
    * @throws Exception Undefined
    */
   public function GetUsername(): string
   {
      return $this->GetByName("dbUsername");
   }

   /**
    * @throws Exception Undefined
    */
   public function GetPassword(): string
   {
      return $this->GetByName("dbPassword");
   }

   /**
    * @throws Exception Undefined
    */
   public function isDbInitGenerateDbWithSampleData(): bool
   {
      return $this->GetByName("dbInitGenerateDbWithSampleData") === "Y";
   }
}
