<?php

declare(strict_types=1);

namespace App\Domain\Transport;

use DomainException;

final readonly class CargoManifest
{
    /**
     * @param array<int, int> $passengerWeights [unitId => weight]
     */
    public function __construct(
        private int $maxCapacity,
        private array $passengerWeights = [],
    ) {
    }

    public function add(int $unitId, int $weight): self
    {
        if ($this->getCurrentWeight() + $weight > $this->maxCapacity) {
            throw new DomainException('Недостаточно места в транспорте');
        }

        $weights = $this->passengerWeights;
        $weights[$unitId] = $weight;

        return new self($this->maxCapacity, $weights);
    }

    public function remove(int $unitId): self
    {
        $weights = $this->passengerWeights;
        unset($weights[$unitId]);

        return new self($this->maxCapacity, $weights);
    }

    public function getCurrentWeight(): int
    {
        return array_sum($this->passengerWeights);
    }

    public function has(int $unitId): bool
    {
        return isset($this->passengerWeights[$unitId]);
    }

    /**
     * @return int[]
     */
    public function getIds(): array
    {
        return array_keys($this->passengerWeights);
    }
}

