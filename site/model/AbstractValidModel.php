<?php

namespace App\model;

abstract class AbstractValidModel
{
   protected array $fieldIsValidFuncArr = [];
   protected array $isFieldReqArr = [];
   protected bool $isValidCache = false;

   public function __construct(public string $childName)
   {
   }

   public function isValid(): array {
      foreach ($this->isFieldReqArr as $i => $isRequiredToCheck) {
         if (! $isRequiredToCheck) {
            continue;
         }

         $isValid = $this->fieldIsValidFuncArr[$i][0]();
         if (! $isValid) {
            return [
               "result" => false,
               "message" => $this->fieldIsValidFuncArr[$i][1],
            ];
         }
      }
      $this->isValidCache = true;
      return [
         "result" => true,
         "message" => "",
      ];
   }

   public function dieWhenInvalid() {
      if ($this->isValidCache) {
         return $this;
      }
      $check = $this->isValid();
      if (! $check["result"]) {
         die("{$this->childName} is invalid! {$check['message']}");
      }
      return $this;
   }

}