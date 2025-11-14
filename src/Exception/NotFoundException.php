<?php

namespace App\Exception;

class NotFoundException extends DomainException
{
    protected string $domainCode = 'NOT_FOUND';

    public function __construct(?string $message = null, int|string|null $variable = null, \Throwable $previous = null)
    {
        $massage = $message ?? 'Не надено данное значение %s';
        $variable = $variable ?? 'NULL';

        parent::__construct(
            404,
            sprintf($massage, $variable),
            $previous);
    }
}
{

}
