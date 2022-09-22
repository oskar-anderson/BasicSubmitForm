<?php

namespace App\viewController\forms;

use App\db\dal\DalSector;
use App\db\dal\DalUserSectorsForm;
use App\db\dal\DalUserSectorsFormSector;
use App\db\DbHelper;
use App\model\UserSectorsForm;
use App\model\UserSectorsFormSector;
use App\util\Base64;

require_once(__DIR__."/../../../vendor/autoload.php");

SectorSubmit::main();

class SectorSubmit
{
   public static function main(): void {
      SectorSubmit::HandlePost();
   }

   public static function HandlePost(): void {
      $name = $_POST['name'] ?? null;
      $sectors = $_POST['sectors'] ?? null;
      $isAgreedToTerms = $_POST['isAgreedToTerms'] ?? false;
      $formId = $_GET['formId'] ?? "";
      $sectors = $sectors === null ? null : json_decode($sectors);

      if ($name === null || $sectors === null || !$isAgreedToTerms) {
         header("Location: http://" . $_SERVER['HTTP_HOST'] ."/site/viewController/index.php");
         return;
      }

      $errors = [];
      if (strlen($name) < 3) {
         array_push($errors, "Name must be at least 3 chars long!");
      }
      if (sizeof($sectors) === 0) {
         array_push($errors, "No sectors selected!");
      }
      if ($isAgreedToTerms === false) {
         array_push($errors, "Is not agreed to terms!");
      }
      if (sizeof($errors) === 0){
         $pdo = DbHelper::getPDO();
         if ($formId !== "") {
            $userSectorsForm = (new DalUserSectorsForm())->get($formId);
            if ($userSectorsForm === null) {
               header("Location: http://" . $_SERVER['HTTP_HOST'] ."/site/viewController/index.php");
               return;
            }
            (new DalUserSectorsFormSector())->deleteByUserSectorsFormId($formId);


            $selectedSectors = static::getSelectedSectors($sectors, $userSectorsForm->getId());

            $updatedUserSectorsForm = (new UserSectorsForm())->
               setId($userSectorsForm->getId())->
               setName($name)->
               setIsAgreedToTerms($isAgreedToTerms);

            (new DalUserSectorsForm())->update($updatedUserSectorsForm);
            (new DalUserSectorsFormSector())->insertByChunk($selectedSectors, $pdo);

            header("Location: http://" . $_SERVER['HTTP_HOST'] ."/site/viewController/index.php?formId=".$userSectorsForm->getId());
            return;
         }

         $userSectorsFormId = Base64::GenerateId();
         $selectedSectors = static::getSelectedSectors($sectors, $userSectorsFormId);
         $userSectorsForm = (new UserSectorsForm())->
            setId($userSectorsFormId)->
            setName($name)->
            setIsAgreedToTerms($isAgreedToTerms);


         (new DalUserSectorsForm())->insertByChunk([$userSectorsForm], $pdo);
         (new DalUserSectorsFormSector())->insertByChunk($selectedSectors, $pdo);

         header("Location: http://" . $_SERVER['HTTP_HOST'] ."/site/viewController/index.php?formId=$userSectorsFormId");
         return;
      }

      session_start();
      $_SESSION["errors"] = json_encode($errors);
      header("Location: http://" . $_SERVER['HTTP_HOST'] ."/site/viewController/index.php");
   }

   /**
    * @return array|void
    */
   public static function getSelectedSectors(&$sectors, string $userSectorsFormId) {
      $selectedSectors = [];
      $dalSectors = (new DalSector())->GetAll();
      $dalSectorIds = array_map(fn($x) => $x->getId(), $dalSectors);
      foreach ($sectors as $sector) {
         if (! in_array($sector, $dalSectorIds, true)) {
            // Unexpected
            header("Location: http://" . $_SERVER['HTTP_HOST'] ."/site/viewController/index.php");
            return;
         }

         $userSectorsFormSector = (new UserSectorsFormSector())->
         setId(Base64::GenerateId())->
         setUserSectorsFormId($userSectorsFormId)->
         setSectorId($sector);
         $selectedSectors[] = $userSectorsFormSector;
      }
      return $selectedSectors;
   }
}