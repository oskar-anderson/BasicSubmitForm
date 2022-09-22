<?php

namespace App\db\dal;

use App\db\DbHelper;
use App\model\UserSectorsForm;
use PDO;
use PDOException;

class DalUserSectorsForm extends AbstractDalBase
{

   public function getTableName(): string { return "user_sectors_form"; }

   public function sqlCreateTableStmt(): string
   {
      $result = "CREATE TABLE " . $this->getDatabaseNameDotTableName() .
         " ( " .
         "Id CHAR(22) NOT NULL PRIMARY KEY, " .
         "Name VARCHAR(255) NOT NULL, " .
         "IsAgreedToTerms TINYINT(1) NOT NULL " .  // this is pointless - always true
         " ) DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_bin;"; // make string comparison case sensitive
      return $result;
   }

   public function map(array $value): UserSectorsForm
   {
      return (new UserSectorsForm())->
         setId($value['Id'])->
         setName($value['Name'])->
         setIsAgreedToTerms($value['IsAgreedToTerms'])->
         dieWhenInvalid();
   }

   public function get($id): UserSectorsForm|null {
      $qry = "SELECT Id, " .
         "Name, " .
         "IsAgreedToTerms " .
         " FROM " . $this->GetDatabaseNameDotTableName() .
         " WHERE Id = ?;";
      $pdo = DbHelper::GetPDO();
      $stmt = $pdo->prepare($qry);
      $stmt->execute([$id]);
      $value = $stmt->fetch();
      if (!$value) {
         return null;
      }
      return $this->Map($value);
   }

   /**
    * @param UserSectorsForm[] $objects
    * @param PDO $pdo
    * @return void
    */
   protected function insert(array $objects, PDO $pdo): void
   {
      $qry = "INSERT INTO " . $this->getDatabaseNameDotTableName() .
         " ( " .
         "Id, " .
         "Name, " .
         "IsAgreedToTerms" .
         " ) VALUES " . $this->getPlaceHolders(numberOfQuestionMarks: 3, numberOfRows: sizeof($objects)) . ";";
      $stmt = $pdo->prepare($qry);
      $params = [];
      foreach ($objects as $object) {
         array_push($params, $object->getId(), $object->getName(), $object->getIsAgreedToTerms());
      }
      $stmt->execute($params);
   }

   public function delete(string $id): void
   {
      $pdo = DbHelper::getPDO();

      (new DalUserSectorsFormSector())->DeleteByUserSectorsFormId($id);

      $qry = "DELETE FROM " . $this->getDatabaseNameDotTableName() . " WHERE Id = ?";
      $stmt = $pdo->prepare($qry);
      $stmt->execute([$id]);
   }

   /**
    * @param UserSectorsForm $object
    */
   public function update($object): void
   {
      $pdo = DbHelper::getPDO();
      $qry = "UPDATE " . $this->getDatabaseNameDotTableName() . " SET " .
         "Id = ?, " .
         "Name = ?, " .
         "IsAgreedToTerms = ? " .
         "WHERE Id = ? ";
      $stmt = $pdo->prepare($qry);
      $stmt->execute([
         $object->getId(), $object->getName(), $object->getIsAgreedToTerms(),
         $object->getId()
      ]);
   }
}