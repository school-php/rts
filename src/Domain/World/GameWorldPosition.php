<?php

declare(strict_types=1);

namespace App\Domain\World;

final class GameWorldPosition
{
    private function __construct(
        private int $x,
        private int $y,
    ) {
    }

    public static function create(int $x, int $y): self
    {
        return new self($x, $y);
    }

    public function getX(): int
    {
        return $this->x;
    }

    public function getY(): int
    {
        return $this->y;
    }
}

