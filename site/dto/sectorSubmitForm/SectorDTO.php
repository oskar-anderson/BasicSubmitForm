<?php /** @noinspection PhpArrayPushWithOneElementInspection */

namespace App\dto\sectorSubmitForm;

use App\model\AbstractValidModel;
use App\model\Sector;
use JsonSerializable;

class SectorDTO extends AbstractValidModel implements JsonSerializable
{
   private ?string $id;
   private ?string $parentId;
   private ?string $name;
   /** @var SectorDTO[] */
   private array $children = [];

   public function __construct()
   {
      parent::__construct("SectorDTO");
      $this->isFieldReqArr = [ true, false, true, true ];
      // Arrow Functions PHP 7.4
      $this->fieldIsValidFuncArr = [
         [fn() => $this->id !== null, "SectorDTO id must be defined!"],
         [fn() => $this->parentId !== null, "SectorDTO parentId must be defined!"],
         [fn() => $this->name !== null, "SectorDTO name must be defined!"],
         [fn() => $this->children !== null, "SectorDTO children must be defined!"]
      ];
   }

   public function setId(string $id): static { $this->isValidCache = false; $this->id = $id; return $this; }

   public function setParentId(?string $parentId): static { $this->isValidCache = false; $this->parentId = $parentId; return $this; }

   public function setName(?string $name): static { $this->isValidCache = false; $this->name = $name; return $this; }

   /** @param SectorDTO[] $children */
   public function setChildren(array $children): static { $this->isValidCache = false; $this->children = $children; return $this; }

   public function getId(): string { $this->dieWhenInvalid(); return $this->id; }

   public function getParentId(): ?string { $this->dieWhenInvalid(); return $this->parentId; }

   public function getName(): string { $this->dieWhenInvalid(); return $this->name; }

   /** @return  SectorDTO[] $children */
   public function getChildren(): array { $this->dieWhenInvalid(); return $this->children; }


   /**
    * @param Sector[] $modelSections
    * @return SectorDTO[]
    */
   public function mapModelToDTO(array $modelSections): array {
      $dict = [];  // assoc array id with children arr
      foreach ($modelSections as $modelSection) {
         $dict[$modelSection->getId()] = [];
      }
      $sectorDTOArr = [];
      foreach ($modelSections as $modelSection) {
         $sectorDTO = (new SectorDTO())->
            setId($modelSection->getId())->
            setName($modelSection->getName())->
            setParentId($modelSection->getParentId());

         if ($modelSection->getParentId() !== null) {
            array_push($dict[$modelSection->getParentId()], $sectorDTO);  // add child
         }
         array_push($sectorDTOArr, $sectorDTO);
      }

      $result = [];  // regular array
      foreach ($sectorDTOArr as $modelSection) {
         $modelSection->setChildren($dict[$modelSection->getId()]);
         if ($modelSection->getParentId() === null) {
            array_push($result, $modelSection);
         }
      }

      return $result;
   }


   public function jsonSerialize(): array
   {
      return [
         "id" => $this->id,
         "parentId" => $this->parentId,
         "name" => $this->name,
         "children" => $this->children
      ];
   }
}