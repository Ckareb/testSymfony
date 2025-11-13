<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

abstract class DomainException extends HttpException
{
    protected string $domainCode;

    public function getDomainCode(): string
    {
        return $this->domainCode ?? 'UNKNOWN_ERROR';
    }
}
