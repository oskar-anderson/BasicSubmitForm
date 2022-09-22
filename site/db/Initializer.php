<?php /** @noinspection PhpArrayPushWithOneElementInspection */


namespace App\db;

require_once(__DIR__."/../../vendor/autoload.php");


use App\db\dal\DalSector;
use App\model\Sector;
use App\util\Base64;
use App\util\Config;
use App\Util\Console;

// Run this from terminal
// php -r "require './Initializer.php'; App\db\Initializer::Initialize();"

// Script class to generate initial database, call from command line
class Initializer
{
   public static function Initialize(): void {
      $console = (new Console(Console::$Linefeed, true));
      $console->writeLine();
      $pdo = DbHelper::getPDO();
      $name = (new Config())->getDatabaseName();
      $dropStatement = "DROP DATABASE IF EXISTS {$name};";
      $console->writeLine($dropStatement);
      $pdo->exec($dropStatement);
      $createStatement = "CREATE DATABASE IF NOT EXISTS {$name};";
      $console->writeLine($createStatement);
      $pdo->exec($createStatement);

      $console->writeLine("Creating tables...");
      DbHelper::createTables();
      if ((new Config())->isDbInitGenerateDbWithSampleData()) {
         $console->writeLine("Initializing data...");
         Initializer::initializeData();
      }
      $console->writeLine("All good!");
   }

   public static function initializeData(): void
   {
      $file = fopen("backupCSV/202209211455-V0_0_1/Sectors.csv","r");
      $sectors = Sector::newArray();
      for($i = 0; $line = fgetcsv($file, separator: ";"); $i++)
      {
         if ($i === 0) continue; // skip first line

         $id = (string) $line[0];
         $parentId = (string) $line[1];
         $parentId = $parentId === "null" ? null : $parentId;
         $name = (string) $line[2];
         array_push($sectors, (new Sector())->
            setId($id)->
            setParentId($parentId)->
            setName($name)
         );
      }
      fclose($file);

      $pdo = DbHelper::getPDO();
      $pdo->beginTransaction();
      $console = (new Console(Console::$Linefeed, true));


      $console->WriteLine('Transaction adding table ' . (new DalSector())->getDatabaseNameDotTableName() . ' : ' . sizeof($sectors));
      (new DalSector())->insertByChunk($sectors, $pdo);


      $pdo->commit();
   }
}
