<?php

declare(strict_types=1);

namespace App\Domain\Battle;

use App\Domain\Battle\Exception\UnitIsNotAliveException;
use InvalidArgumentException;

final readonly class HP
{
    private function __construct(
        private int $current,
        private int $max,
    ) {
        if ($current < 0 || $current > $max) {
            throw new InvalidArgumentException();
        }
    }

    public static function create(int $current, int $max): self
    {
        return new self($current, $max);
    }

    /**
     * Сохранён для совместимости с исходным кодом.
     */
    public static function Setup(int $current, int $max): self
    {
        return self::create($current, $max);
    }

    public function increase(int $hp): self
    {
        if (!$this->isAlive()) {
            throw new UnitIsNotAliveException();
        }

        return new self(
            min($this->current + $hp, $this->max),
            $this->max,
        );
    }

    public function decrease(int $hp): self
    {
        return new self(
            max($this->current - $hp, 0),
            $this->max,
        );
    }

    public function isAlive(): bool
    {
        return $this->current > 0;
    }

    public function getValue(): int
    {
        return $this->current;
    }
}

