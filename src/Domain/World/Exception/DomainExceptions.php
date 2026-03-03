<?php

declare(strict_types=1);

namespace App\Domain\World\Exception;

use DomainException;

class InvalidWorldPositionException extends DomainException
{
}

class NotActiveWorldExpection extends DomainException
{
}

