<?php

namespace App\db;

require_once(__DIR__."/../../vendor/autoload.php");


use App\db\dal\AbstractDalBase;
use App\db\dal\DalSector;
use App\db\dal\DalUserSectorsForm;
use App\db\dal\DalUserSectorsFormSector;
use App\util\Config as Config;
use PDO;
use PDOException;
use App\Util\Console;

class DbHelper {

   /**
    *  @return AbstractDalBase[]
    */
   private static function GetTrackedTables(): array
   {
      return [
         new DalSector(),
         new DalUserSectorsFormSector(),
         new DalUserSectorsForm(),
      ];
   }

    public static function createTables(): void
    {
       $createTableStatements = array_map(fn(AbstractDalBase $x) => $x->sqlCreateTableStmt(), DbHelper::GetTrackedTables());
       $pdo = DbHelper::getPDO();
       foreach ($createTableStatements as $i=>$createTableStatement) {
          (new Console(Console::$Linefeed, true))->writeLine($i + 1 . "/" . count($createTableStatements) . ": " . $createTableStatement);
          $pdo->query($createTableStatement);
       }
    }

   public static function getPDO(): PDO
   {
      $config = new Config();
      try {
         $pdo = new PDO($config->GetConnectDsn(), $config->GetUsername(), $config->GetPassword());
         $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         return $pdo;
      } catch (PDOException $e) {
         (new Console(Console::$Linefeed, true))->writeLine("GetPdoByKey FAILED ({$e->getMessage()})");
         throw $e;
      }
   }
}
