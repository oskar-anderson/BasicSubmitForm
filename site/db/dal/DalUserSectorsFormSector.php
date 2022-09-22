<?php

namespace App\db\dal;

use App\db\DbHelper;
use App\model\UserSectorsForm;
use App\model\UserSectorsFormSector;
use PDO;
use PDOException;

// m2m relation
class DalUserSectorsFormSector extends AbstractDalBase
{

   public function getTableName(): string { return "user_sectors_form_sector"; }

   public function sqlCreateTableStmt(): string
   {
      $result = "CREATE TABLE " . $this->getDatabaseNameDotTableName() .
         " ( " .
         "Id CHAR(22) NOT NULL PRIMARY KEY, " .
         "UserSectorsFormId CHAR(22) NOT NULL, " .
         "SectorId CHAR(22) NOT NULL, " .
         "CONSTRAINT " . DalUserSectorsFormSector::GetTableName() ."FKSectorId foreign key (SectorId) references " . (new DalSector())->GetTableName() . "(Id)" .
         " ) DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_bin;"; // make string comparison case sensitive
      return $result;
   }

   public function map(array $value): UserSectorsFormSector
   {
      return (new UserSectorsFormSector())->
         setId($value['Id'])->
         setUserSectorsFormId($value['UserSectorsFormId'])->
         setSectorId($value['SectorId'])->
         dieWhenInvalid();
   }

   /**
    * @param string $userSectorsFormId
    * @return UserSectorsFormSector[]
    */
   public function getByUserSectorsFormId(string $userSectorsFormId): array {
      $qry = "SELECT Id, " .
         "Id, " .
         "UserSectorsFormId, " .
         "SectorId " .
         " FROM " . $this->GetDatabaseNameDotTableName() .
         " WHERE UserSectorsFormId = ?;";
      $pdo = DbHelper::GetPDO();
      $stmt = $pdo->prepare($qry);
      $stmt->execute([$userSectorsFormId]);
      $result = [];
      while ($value = $stmt->fetch()) {
         array_push($result, $this->map($value));
      }
      return $result;
   }


   /**
    * @param UserSectorsFormSector[] $objects
    * @param PDO $pdo
    */
   protected function insert(array $objects, PDO $pdo): void
   {
      $qry = "INSERT INTO " . $this->getDatabaseNameDotTableName() .
         " ( " .
         "Id, " .
         "UserSectorsFormId, " .
         "SectorId " .
         " ) VALUES " . $this->getPlaceHolders(numberOfQuestionMarks: 3, numberOfRows: sizeof($objects)) . ";";
      $stmt = $pdo->prepare($qry);
      $params = [];
      foreach ($objects as $object) {
         array_push($params, $object->getId(), $object->getUserSectorsFormId(), $object->getSectorId());
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

   public function deleteByUserSectorsFormId(string $userSectorsFormId): void {
      $pdo = DbHelper::getPDO();
      $qry = "DELETE FROM " . $this->getDatabaseNameDotTableName() . " WHERE UserSectorsFormId = ?";
      $stmt = $pdo->prepare($qry);
      $stmt->execute([$userSectorsFormId]);
   }

   /**
    * @param UserSectorsFormSector $object
    * @return void
    */
   public function update($object): void
   {
      $pdo = DbHelper::getPDO();
      $qry = "UPDATE " . $this->getDatabaseNameDotTableName() . " SET " .
         "Id = ?, " .
         "UserSectorsFormId = ?, " .
         "SectorId = ? " .
         "WHERE Id = ? ";
      $stmt = $pdo->prepare($qry);
      $stmt->execute([
         $object->getId(), $object->getUserSectorsFormId(), $object->getSectorId(),
         $object->getId()
      ]);
   }
}