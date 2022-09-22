<?php

namespace App\model;

class UserSectorsForm extends AbstractValidModel
{
   private ?string $id;
   private ?string $name;
   private ?bool $isAgreedToTerms;

   public function __construct()
   {
      parent::__construct("UserSectorsForm");
      $this->isFieldReqArr = [ true, true, true ];
      // Arrow Functions PHP 7.4
      $this->fieldIsValidFuncArr = [
         [fn() => $this->id !== null, "UserSectorsForm id must be defined!"],
         [fn() => $this->name !== null, "UserSectorsForm name must be defined!"],
         [fn() => $this->isAgreedToTerms !== null, "UserSectorsForm isAgreedToTerms must be defined!"]
      ];
   }

   public function setId(string $id): static { $this->isValidCache = false; $this->id = $id; return $this; }

   public function setName(string $name): static { $this->isValidCache = false; $this->name = $name; return $this; }

   public function setIsAgreedToTerms(bool $isAgreedToTerms): static { $this->isValidCache = false; $this->isAgreedToTerms = $isAgreedToTerms; return $this; }

   public function getId(): string { $this->dieWhenInvalid(); return $this->id; }

   public function getName(): string { $this->dieWhenInvalid(); return $this->name; }

   public function getIsAgreedToTerms(): bool { $this->dieWhenInvalid(); return $this->isAgreedToTerms; }
}