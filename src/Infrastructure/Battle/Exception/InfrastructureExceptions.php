<?php

declare(strict_types=1);

namespace App\Infrastructure\Battle\Exception;

use DomainException;

class UnitNotFoundException extends DomainException
{
    public function __construct(int $unitId)
    {
        parent::__construct("Unit {$unitId} not found");
    }
}

class UnitIncorrectTypeException extends DomainException
{
    public function __construct(int $unitId, string $expected)
    {
        parent::__construct("Unit {$unitId} is not instance of {$expected}");
    }
}

class PersistableNotFoundException extends DomainException
{
}

