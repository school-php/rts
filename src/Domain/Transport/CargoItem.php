<?php

declare(strict_types=1);

namespace App\Domain\Transport;

use InvalidArgumentException;

final readonly class CargoItem
{
    public function __construct(
        private int $itemId,
        private int $weight,
    ) {
        if ($weight <= 0) {
            throw new InvalidArgumentException('Weight must be positive');
        }
    }

    public function id(): int
    {
        return $this->itemId;
    }

    public function weight(): int
    {
        return $this->weight;
    }

    public function equals(self $other): bool
    {
        return $this->itemId === $other->itemId;
    }
}

