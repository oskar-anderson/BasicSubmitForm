<?php

declare(strict_types=1);

namespace App\viewController;

use App\db\dal\DalSector;
use App\db\dal\DalUserSectorsForm;
use App\db\dal\DalUserSectorsFormSector;
use App\dto\sectorSubmitForm\SectorDTO;
use App\dto\sectorSubmitForm\SectorSubmitFormDTO;
use App\util\Output;
use Exception;

require_once(__DIR__."/../../vendor/autoload.php");


(new index())->main();

class index {

   public static function main():void {
      $formId = $_GET['formId'] ?? "";

      $sectors = (new DalSector())->GetAll();
      $sectorsDto = (new SectorDTO())->mapModelToDTO($sectors);
      $userSectorsFormSectorIds = [];
      $userSectorsForm = null;
      if ($formId !== "") {
         $userSectorsForm = (new DalUserSectorsForm())->get($formId);
         $userSectorsFormSector = (new DalUserSectorsFormSector())->getByUserSectorsFormId($formId);
         $userSectorsFormSectorIds = array_map(fn($x) => $x->getSectorId(), $userSectorsFormSector);
      }


      $sectorsDtoStrArr = [];
      static::GetOptionLevel($sectorsDto, 0, $sectorsDtoStrArr, $userSectorsFormSectorIds);
      $model = new SectorSubmitFormDTO($sectorsDtoStrArr, $formId, $userSectorsForm);
      Output::Render(__DIR__ . "/../view/main/index.php", $model);
   }

   /**
    * @param SectorDTO[] $sectorDTOs
    * @param int $level
    * @param array $sectorsDtoStrArr
    * @param string[] $prevSessionChecked
    * @return void
    */
   public static function GetOptionLevel(array $sectorDTOs, int $level, &$sectorsDtoStrArr=[], array &$prevSessionChecked=[]): void {

      foreach ($sectorDTOs as $sectorDTO) {

         if (sizeof($sectorDTO->getChildren()) !== 0) {
            $padding = "padding-left: " . (20 * $level) . "px";
            $style = "style='$padding;'";
            $sectorsDtoStrArr[] = "<div {$style}><span style='font-weight: bold;'>". htmlspecialchars($sectorDTO->getName()) ."</span>";
            static::GetOptionLevel($sectorDTO->getChildren(), $level + 1, $sectorsDtoStrArr, $prevSessionChecked);
            $sectorsDtoStrArr[] = "</div>";
         } else {
            $padding = "padding-left: 20px";
            $style = "style='$padding;'";
            $checked = in_array($sectorDTO->getId(), $prevSessionChecked, true) ? "checked" : "";
            $sectorsDtoStrArr[] = "<label {$style} class='w-100'><input style='margin: 4px 4px 4px 0;' type='checkbox' $checked value='" . $sectorDTO->getId() . "'>" . htmlspecialchars($sectorDTO->getName()) . "</input></label>";
         }
      }
   }
}
