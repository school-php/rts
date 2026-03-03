<?php

declare(strict_types=1);

namespace App\Domain\Battle\Exception;

use DomainException;

class NoAllowMovepointsException extends DomainException
{
}

class AttackableBlockException extends DomainException
{
}

class NotAliveException extends DomainException
{
}

class UnitIsNotAliveException extends DomainException
{
}

class NotInRangeException extends DomainException
{
}

