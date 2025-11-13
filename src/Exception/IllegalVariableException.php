<?php

namespace App\Exception;

class IllegalVariableException extends DomainException
{
    protected string $domainCode = 'BAD_REQUEST';

    public function __construct(int|string $variable, \Throwable $previous = null)
    {
        parent::__construct(400, sprintf('Данное поле не может иметь данно значение %s', $variable), $previous);
    }
}
{

}
