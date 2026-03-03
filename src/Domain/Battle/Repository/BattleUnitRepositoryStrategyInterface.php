<?php

declare(strict_types=1);

namespace App\Domain\Battle\Repository;

use App\Domain\Battle\BattleUnitInterface;

interface BattleUnitRepositoryStrategyInterface
{
    /**
     * @template T of BattleUnitInterface
     *
     * @param int $id
     * @param class-string<T> $type
     *
     * @return T
     */
    public function getByIdAs(int $id, string $type): BattleUnitInterface;

    public function save(BattleUnitInterface $unit): void;
}

