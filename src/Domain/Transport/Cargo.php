<?php

declare(strict_types=1);

namespace App\Domain\Transport;

use RuntimeException;

final class Cargo
{
    private int $maxTotalWeight;

    private int $maxUnitWeight;

    /** @var array<string, int> unitId => weight */
    private array $units = [];

    public function __construct(int $maxTotalWeight, int $maxUnitWeight)
    {
        if ($maxTotalWeight <= 0 || $maxUnitWeight <= 0) {
            throw new RuntimeException('Weight limits must be positive');
        }

        $this->maxTotalWeight = $maxTotalWeight;
        $this->maxUnitWeight = $maxUnitWeight;
    }

    public function canLoadUnit(int $unitId, int $weight): bool
    {
        return $weight <= $this->maxUnitWeight
            && $this->getCurrentLoad() + $weight <= $this->maxTotalWeight;
    }

    public function loadUnit(int $unitId, int $weight): void
    {
        if (!$this->canLoadUnit($unitId, $weight)) {
            throw new RuntimeException('Cannot load unit: weight exceeded');
        }

        $this->units[$unitId] = $weight;
    }

    public function unloadUnit(int $unitId): void
    {
        unset($this->units[$unitId]);
    }

    public function getCurrentLoad(): int
    {
        return array_sum($this->units);
    }

    /** @return array<string,int> */
    public function getLoadedUnits(): array
    {
        return $this->units;
    }

    public function getMaxTotalWeight(): int
    {
        return $this->maxTotalWeight;
    }

    public function getMaxUnitWeight(): int
    {
        return $this->maxUnitWeight;
    }
}

