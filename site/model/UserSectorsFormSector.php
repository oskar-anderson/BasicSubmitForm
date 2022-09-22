<?php

namespace App\model;

class UserSectorsFormSector extends AbstractValidModel
{
   private ?string $id;
   private ?string $userSectorsFormId;
   private ?string $sectorId;

   public function __construct()
   {
      parent::__construct("UserSectorsFormSector");
      $this->isFieldReqArr = [ true, true, true ];
      // Arrow Functions PHP 7.4
      $this->fieldIsValidFuncArr = [
         [fn() => $this->id !== null, "UserSectorsFormSector id must be defined!"],
         [fn() => $this->userSectorsFormId !== null, "UserSectorsFormSector userSectorsFormId must be defined!"],
         [fn() => $this->sectorId !== null, "UserSectorsFormSector sectorId must be defined!"]
      ];
   }

   public function setId(string $id): static { $this->isValidCache = false; $this->id = $id; return $this; }

   public function setUserSectorsFormId(?string $userSectorsFormId): static { $this->isValidCache = false; $this->userSectorsFormId = $userSectorsFormId; return $this; }

   public function setSectorId(string $sectorId): static { $this->isValidCache = false; $this->sectorId = $sectorId; return $this; }

   public function getId(): string { $this->dieWhenInvalid(); return $this->id; }

   public function getUserSectorsFormId(): string { $this->dieWhenInvalid(); return $this->userSectorsFormId; }

   public function getSectorId(): string { $this->dieWhenInvalid(); return $this->sectorId; }
}