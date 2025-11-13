<?php

namespace App\Dto;
use Symfony\Component\Serializer\Annotation\Groups;

class ContractDtoWithSpec extends ContractDto
{
    public array $contractSpec = [];

    public function getContractSpec(): ?array { return $this->contractSpec; }
    public function setContractSpec(?array $contractSpec): void { $this->contractSpec = $contractSpec; }
}
