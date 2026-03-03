<?php

declare(strict_types=1);

namespace App\Domain\Battle;

use InvalidArgumentException;

final readonly class Attack
{
    private function __construct(
        private int $damage,
        private int $range,
    ) {
        if ($damage < 0) {
            throw new InvalidArgumentException('Damage cannot be negative');
        }

        if ($range < 0) {
            throw new InvalidArgumentException('Range cannot be negative');
        }
    }

    public static function create(int $damage, int $range): self
    {
        return new self($damage, $range);
    }

    public function getDamage(): int
    {
        return $this->damage;
    }

    public function getRange(): int
    {
        return $this->range;
    }
}

