<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 't_contract_spec', schema: "edu_php")]
class ContractSpecEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'bigint')]
    private ?string $id = null;

    #[ORM\Column(type: 'string', length: 20)]
    private string $code;

    #[ORM\Column(type: 'string', length: 100)]
    private string $name;

    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeImmutable $createDate;

    #[ORM\Column(type: 'decimal')]
    private ?string $price;

    #[ORM\Column(type: 'integer')]
    private ?int $quantity;

    #[ORM\ManyToOne(targetEntity: ContractEntity::class, inversedBy: 't_contract_spec')]
    #[ORM\JoinColumn(name: 'contract_id', referencedColumnName: 'id')]
    private ?ContractEntity $contract = null;

    public function getId(): ?string { return $this->id; }
    public function setId(?string $id): void { $this->id = $id; }

    public function getCode(): string { return $this->code; }
    public function setCode(string $code): void { $this->code = $code; }

    public function getName(): string { return $this->name; }
    public function setName(string $name): void { $this->name = $name; }

    public function getCreateDate(): \DateTimeImmutable { return $this->createDate; }
    public function setCreateDate(\DateTimeImmutable $createDate): void { $this->createDate = $createDate; }

    public function getPrice(): ?string { return $this->price; }
    public function setPrice(?string $price): void { $this->price = $price; }

    public function getQuantity(): ?int { return $this->quantity; }
    public function setQuantity(?int $quantity): void { $this->quantity = $quantity; }

    public function getContract(): ?ContractEntity { return $this->contract; }
    //public function setContract(?ContractEntity $contract): void { $this->contract = $contract; }
}
