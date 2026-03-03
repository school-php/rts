<?php

declare(strict_types=1);

namespace App\Domain\Battle;

trait AttackableTrait
{
    private Attack $attack;

    public function getDamage(): int
    {
        return $this->attack->getDamage();
    }

    public function getRange(): int
    {
        return $this->attack->getRange();
    }
}

