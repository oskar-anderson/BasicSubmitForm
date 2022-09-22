<?php

namespace App\db\dal;

use App\db\DbHelper;
use App\model\Sector;
use PDO;

class DalSector extends AbstractDalBase
{

   public function getTableName(): string { return "sectors"; }

   public function sqlCreateTableStmt(): string
   {
      $result = "CREATE TABLE " . $this->getDatabaseNameDotTableName() .
         " ( " .
         "Id CHAR(22) NOT NULL PRIMARY KEY, " .
         "ParentID CHAR(22), " .
         "Name VARCHAR(255) NOT NULL " .
         " ) DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_bin;"; // make string comparison case sensitive
      return $result;
   }

   public function map(array $value): Sector
   {
      return (new Sector())->
         setId($value['Id'])->
         setParentId($value['ParentID'])->
         setName($value['Name'])->
         dieWhenInvalid();
   }

   /**
    *  @return Sector[]
    */
   public function GetAll(): array {
      $qry = "SELECT Id, " .
         "ParentID, " .
         "Name " .
         " FROM " . $this->GetDatabaseNameDotTableName() . ";";
      $pdo = DbHelper::GetPDO();
      $res = $pdo->query($qry);
      $result = Sector::newArray();
      while ($value = $res->fetch()) {
         array_push($result, $this->Map($value));
      }
      return $result;
   }

   /**
    * @param Sector[] $objects
    * @param PDO $pdo
    */
   protected function insert(array $objects, PDO $pdo): void
   {
      $qry = "INSERT INTO " . $this->getDatabaseNameDotTableName() .
         " ( " .
         "Id, " .
         "ParentID, " .
         "Name" .
         " ) VALUES " . $this->getPlaceHolders(numberOfQuestionMarks: 3, numberOfRows: sizeof($objects)) . ";";
      $stmt = $pdo->prepare($qry);
      $params = [];
      foreach ($objects as $object) {
         array_push($params, $object->getId(), $object->getParentId(), $object->getName());
      }
      $stmt->execute($params);
   }

   public function delete(string $id): void
   {
      $pdo = DbHelper::getPDO();
      $qry = "DELETE FROM " . $this->getDatabaseNameDotTableName() . " WHERE Id = ?";
      $stmt = $pdo->prepare($qry);
      $stmt->execute([$id]);
   }


   /**
    * @param Sector $object
    */
   public function update($object): void
   {
      $pdo = DbHelper::getPDO();
      $qry = "UPDATE " . $this->getDatabaseNameDotTableName() . " SET " .
         "Id = ?, " .
         "ParentId = ?, " .
         "Name = ? " .
         "WHERE Id = ? ";
      $stmt = $pdo->prepare($qry);
      $stmt->execute([
         $object->getId(), $object->getParentId(), $object->getName(),
         $object->getId()
      ]);
   }
}