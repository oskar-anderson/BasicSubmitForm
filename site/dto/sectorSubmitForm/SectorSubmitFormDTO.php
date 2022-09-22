<?php

namespace App\dto\sectorSubmitForm;

use App\model\UserSectorsForm;

class SectorSubmitFormDTO
{
   /**
    * @param string[] $sectorFlatOptionGroupsAndOption
    * @param string $formId
    * @param UserSectorsForm|null $userSectorsForm
    */
   public function __construct(public array $sectorFlatOptionGroupsAndOption,
                               public string $formId,
                               public UserSectorsForm|null $userSectorsForm

   )
   {

   }
}