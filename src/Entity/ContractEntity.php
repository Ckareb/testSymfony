<?php

namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 't_contract', schema: "edu_php")]
class ContractEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'bigint')]
    private ?string $id = null;
//    #[ORM\Id]
//    #[ORM\GeneratedValue]
//    #[ORM\Column(type: 'bigint')]
//    private ?string $objectId = null;

    #[ORM\Column(type: 'string', length: 20)]
    private string $code;
//
//    #[ORM\Column(type: 'datetime_immutable')]
//    private ?\DateTimeImmutable $createDate;
//
//    #[ORM\Column(type: 'string', length: 100)]
//    private string $contractNumber;
//
//    #[ORM\Column(type: 'datetime_immutable')]
//    private \DateTimeImmutable $contractDate;
//
//    #[ORM\Column(type: 'bigint')]
//    private string $statusId;

    #[ORM\Column(type: 'string', length: 100)]
    private string $name;

    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeImmutable $statusDate;

    #[ORM\Column(type: 'decimal')]
    private ?string $price;

    #[ORM\Column(type: 'integer')]
    private ?int $quantity;

    #[ORM\OneToMany(mappedBy: 'contract', targetEntity: ContractSpecEntity::class)]
    private Collection $contractSpecs;

//    #[ORM\Column(type: 'bigint')]
//    private string $supContrId;
//
//    #[ORM\Column(type: 'bigint')]
//    private ?string $supContragId;
//
//    #[ORM\Column(type: 'datetime_immutable')]
//    private \DateTimeImmutable $validStartDate;
//
//    #[ORM\Column(type: 'datetime_immutable')]
//    private \DateTimeImmutable $validEndDate;
//
//    #[ORM\Column(type: 'bigint')]
//    private string $contrId;
//
//    #[ORM\Column(type: 'bigint')]
//    private ?string $contrUnitId;
//
//    #[ORM\Column(type: 'bigint')]
//    private ?string $contragId;
//
//    #[ORM\Column(type: 'bigint')]
//    private ?string $currency;
//
//    #[ORM\Column(type: 'string', length: 4000)]
//    private ?string $notes;
//
//    #[ORM\Column(type: 'string', length: 4000)]
//    private ?string $supNotes;
//
//    #[ORM\Column(type: 'bigint')]
//    private ?string $withVat;
//
//    #[ORM\Column(type: 'bigint')]
//    private ?string $classId;
//
//    #[ORM\Column(type: 'datetime_immutable')]
//    private ?\DateTimeImmutable $lastUpdateDate;
//
//    #[ORM\Column(type: 'string', length: 100)]
//    private ?string $createUser;
//
//    #[ORM\Column(type: 'string', length: 100)]
//    private ?string $lastUpdateUser;

    public function getId(): ?string { return $this->id; }
    public function setId(?string $id): void { $this->id = $id; }

//    public function getObjectId(): ?string { return $this->objectId; }
//    public function setObjectId(?string $objectId): void { $this->objectId = $objectId; }

    public function getCode(): string { return $this->code; }
    public function setCode(string $code): void { $this->code = $code; }

//    public function getCreateDate(): ?\DateTimeImmutable { return $this->createDate; }
//    public function setCreateDate(?\DateTimeImmutable $createDate): void { $this->createDate = $createDate; }

//    public function getContractNumber(): string { return $this->contractNumber; }
//    public function setContractNumber(string $contractNumber): void { $this->contractNumber = $contractNumber; }
//
//    public function getContractDate(): \DateTimeImmutable { return $this->contractDate; }
//    public function setContractDate(\DateTimeImmutable $contractDate): void { $this->contractDate = $contractDate; }
//
//    public function getValidStartDate(): \DateTimeImmutable { return $this->validStartDate; }
//    public function setValidStartDate(\DateTimeImmutable $validStartDate): void { $this->validStartDate = $validStartDate; }
//
//    public function getValidEndDate(): \DateTimeImmutable { return $this->validEndDate; }
//    public function setValidEndDate(\DateTimeImmutable $validEndDate): void { $this->validEndDate = $validEndDate; }
//
//    public function getStatusId(): string { return $this->statusId; }
//    public function setStatusId(string $statusId): void { $this->statusId = $statusId; }

    public function getName(): string { return $this->name; }
    public function setName(string $name): void { $this->name = $name; }

    public function getStatusDate(): \DateTimeImmutable { return $this->statusDate; }
    public function setStatusDate(\DateTimeImmutable $statusDate): void { $this->statusDate = $statusDate; }

    public function getPrice(): ?string { return $this->price; }
    public function setPrice(?string $price): void { $this->price = $price; }

    public function getQuantity(): ?int { return $this->quantity; }
    public function setQuantity(?int $quantity): void { $this->quantity = $quantity; }

    public function getContractSpecs(): ?Collection { return $this->contractSpecs; }
    public function setContractSpecs(?Collection $contractSpecs): void { $this->contractSpecs = $contractSpecs; }

//    public function getSupContrId(): string { return $this->supContrId; }
//    public function setSupContrId(string $supContrId): void { $this->supContrId = $supContrId; }
//
//    public function getSupContragId(): ?string { return $this->supContragId; }
//    public function setSupContragId(?string $supContragId): void { $this->supContragId = $supContragId; }
//
//    public function getContrId(): string { return $this->contrId; }
//    public function setContrId(string $contrId): void { $this->contrId = $contrId; }
//
//    public function getContrUnitId(): ?string { return $this->contrUnitId; }
//    public function setContrUnitId(?string $contrUnitId): void { $this->contrUnitId = $contrUnitId; }
//
//    public function getContractagId(): ?string { return $this->contragId; }
//    public function setContractagId(?string $contragId): void { $this->contragId = $contragId; }
//
//    public function getCurrency(): ?string { return $this->currency; }
//    public function setCurrency(?string $currency): void { $this->currency = $currency; }
//
//    public function getNotes(): ?string { return $this->notes; }
//    public function setNotes(?string $notes): void { $this->notes = $notes; }
//
//    public function getSupNotes(): ?string { return $this->supNotes; }
//    public function setSupNotes(?string $supNotes): void { $this->supNotes = $supNotes; }
//
//    public function getWithVat(): ?string { return $this->withVat; }
//    public function setWithVat(?string $withVat): void { $this->withVat = $withVat; }
//
//    public function getClassId(): ?string { return $this->classId; }
//    public function setClassId(?string $classId): void { $this->classId = $classId; }
//
//    public function getLastUpdateDate(): ?\DateTimeImmutable { return $this->lastUpdateDate; }
//    public function setLastUpdateDate(?\DateTimeImmutable $lastUpdateDate): void { $this->lastUpdateDate = $lastUpdateDate; }
//
//    public function getCreateUser(): ?string { return $this->createUser; }
//    public function setCreateUser(?string $createUser): void { $this->createUser = $createUser; }
//
//    public function getLastUpdateUser(): ?string { return $this->lastUpdateUser; }
//    public function setLastUpdateUser(?string $lastUpdateUser): void { $this->lastUpdateUser = $lastUpdateUser; }
}
