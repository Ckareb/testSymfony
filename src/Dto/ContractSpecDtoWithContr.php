<?php

namespace App\Dto;

class ContractSpecDtoWithContr extends ContractSpecDto
{
    public ContractDto $contract;

    public function getContract(): ?ContractDto { return $this->contract; }
    public function setContract(?ContractDto $contract): void { $this->contract = $contract; }
}
