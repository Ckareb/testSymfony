<?php

namespace App\Dto;
use App\Entity\ContractEntity;
use Symfony\Component\Serializer\Annotation\Groups;

class ContractSpecDto
{
    private ?string $id = null;

    private string $contractId;

    private string $code;

    private string $name;

    private ?\DateTimeImmutable $createDate;

    private ?string $price;

    private ?int $quantity;

    public function getId(): ?string { return $this->id; }
    public function setId(?string $id): void { $this->id = $id; }

    public function getContractId(): string { return $this->contractId; }
    public function setContractId(string $contractId): void { $this->contractId = $contractId; }

    public function getCode(): string { return $this->code; }
    public function setCode(string $code): void { $this->code = $code; }

    public function getName(): string { return $this->name; }
    public function setName(string $name): void { $this->name = $name; }

    public function getCreateDate(): ?\DateTimeImmutable { return $this->createDate; }
    public function setCreateDate(?\DateTimeImmutable $createDate): void { $this->createDate = $createDate; }

    public function getPrice(): ?string { return $this->price; }
    public function setPrice(?string $price): void { $this->price = $price; }

    public function getQuantity(): ?int { return $this->quantity; }
    public function setQuantity(?int $quantity): void { $this->quantity = $quantity; }
}
