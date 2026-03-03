<?php

declare(strict_types=1);

namespace App\Domain\Battle;

interface Attackable
{
    public function getDamage(): int;

    public function getRange(): int;
}

