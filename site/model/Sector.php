<?php

namespace App\model;

class Sector extends AbstractValidModel
{
   private ?string $id;
   private ?string $parentId = null;
   private ?string $name;

   public function __construct()
   {
      parent::__construct("Sector");
      $this->isFieldReqArr = [ true, false, true ];
      // Arrow Functions PHP 7.4
      $this->fieldIsValidFuncArr = [
         [fn() => $this->id !== null, "Sector id must be defined!"],
         [fn() => $this->parentId !== null, "Sector parentId must be defined!"],
         [fn() => $this->name !== null, "Sector name must be defined!"]
      ];
   }

   public function setId(string $id): static { $this->isValidCache = false; $this->id = $id; return $this; }

   public function setParentId(?string $parentId): static { $this->isValidCache = false; $this->parentId = $parentId; return $this; }

   public function setName(string $name): static { $this->isValidCache = false; $this->name = $name; return $this; }

   public function getId(): string { $this->dieWhenInvalid(); return $this->id; }

   public function getParentId(): ?string { $this->dieWhenInvalid(); return $this->parentId; }

   public function getName(): string { $this->dieWhenInvalid(); return $this->name; }

   /**
    * @return Sector[]
    */
   public static function newArray(): array {
      return [];
   }
}