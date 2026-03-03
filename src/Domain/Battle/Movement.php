<?php

declare(strict_types=1);

namespace App\Domain\Battle;

use App\Domain\Battle\Exception\NoAllowMovepointsException;

final readonly class Movement
{
    private function __construct(
        private int $maxFuel,
        private int $currentFuel,
        private int $fuelPerMovepoint,
        private int $movepoints,
    ) {
    }

    public static function create(
        int $maxFuel,
        int $currentFuel,
        int $fuelPerMovepoint,
        int $movepoints
    ): self {
        return new self($maxFuel, $currentFuel, $fuelPerMovepoint, $movepoints);
    }

    public function getAllowMovepoints(): int
    {
        if ($this->movepoints * $this->fuelPerMovepoint > $this->currentFuel) {
            return $this->movepoints;
        }

        return (int) ($this->currentFuel / $this->fuelPerMovepoint);
    }

    public function move(int $movepoints): self
    {
        if ($movepoints > $this->getAllowMovepoints()) {
            throw new NoAllowMovepointsException();
        }

        $newMovepoints = $this->movepoints - $movepoints;
        $currentFuel = $this->currentFuel - ($movepoints * $this->fuelPerMovepoint);

        return new self($this->maxFuel, $currentFuel, $this->fuelPerMovepoint, $newMovepoints);
    }
}

