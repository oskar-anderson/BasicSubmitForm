<?php

namespace App\db\dal;

require_once(__DIR__."/../../../vendor/autoload.php");

use App\util\Config;
use App\util\Console;
use PDO;
use PDOException;

abstract class AbstractDalBase {
    /**
     * Table names will be lowercase in DB
    *  @return string
    */
   public abstract function getTableName(): string;

   /**
    *  @return string
    */
    public abstract function sqlCreateTableStmt(): string;

    public function getDatabaseName(): string {
       return (new Config())->getDatabaseName();
    }

    public function getDatabaseNameDotTableName(): string {
       return $this->getDatabaseName() . "." . $this->getTableName();
    }

   public abstract function map(array $value);
   /**
    *  @throws PDOException
    */
   protected abstract function insert(array $objects, PDO $pdo): void;
   public abstract function delete(string $id): void;
   public abstract function update($object): void;


   /**
    * Generates placeholders for inserting arrays to database tables by pdo prepare
    *  @return string
    *
    * Example:
    * getPlaceHolders(3, 5); // (?,?,?),(?,?,?),(?,?,?),(?,?,?),(?,?,?)
    */
   public function getPlaceHolders($numberOfQuestionMarks, $numberOfRows): string {
      $questionMarksInsideParentheses = "(" . implode(",", str_split(str_repeat("?", $numberOfQuestionMarks))) . ")";
      return implode(",", array_fill(0, $numberOfRows, $questionMarksInsideParentheses));
   }


   /**
    * Insert into database by chunks
    *
    * I am not sure why this is needed but without it, we get:
    * Fatal error: Uncaught PDOException: SQLSTATE[HY000]: General error: 2006 MySQL server has gone away
    *
    */
   public function insertByChunk($objects, $pdo): void {
      $chunks = array_chunk($objects, 1000);
      try {
         foreach ($chunks as $i => $chunk) {
            $this->insert($chunk, $pdo);
         }
      }
      catch (PDOException $e) {
         $console = new Console(Console::$BreakLF, true);
         $console->writeLine("PDOException " . $i . "/" . sizeof($chunks) . ": " . $e);
         die();
      }
   }

   /**
    * @param int $numberOfGroups
    * @param int $numberOfColumns
    * @param string $selectQuery
    * @param string $breakValue
    * @return string
    * Select cannot be ordered unfortunately
    * Example 1:
    * INPUT
    * SelectUnionBreak(
    *    $numberOfGroups = 2,
    *    $numberOfColumns = 7,
    *    $selectQuery = 'SELECT * FROM Customers WHERE Country = ?',
    *    $placeholder = "'break'");
    * RESULT
    *
    * SELECT * FROM Customers WHERE Country = ?
      UNION ALL
      SELECT "break", "break", "break", "break", "break", "break", "break"
      UNION ALL
      SELECT * FROM Customers WHERE Country = ?;
    */
   public function selectUnionBreak(int $numberOfGroups, int $numberOfColumns, string $selectQuery, string $breakValue = "'break'"): string {
      $selectQueries = array_fill(0, $numberOfGroups, $selectQuery);
      $sep = " UNION ALL SELECT " . join(", ", array_fill(0, $numberOfColumns, $breakValue)) . " UNION ALL ";
      return join($sep, $selectQueries) . ";";
   }
}
