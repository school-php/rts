<?php

declare(strict_types=1);

namespace App\Application\Battle;

use App\Domain\Battle\Attackable;
use App\Domain\Battle\BattleUnit;
use App\Domain\Battle\Exception\NotAliveException;
use App\Domain\Battle\Exception\NotInRangeException;
use App\Domain\Battle\Repository\BattleUnitRepositoryStrategyInterface;
use App\Infrastructure\World\WorldRepository;

final class AttackUseCase
{
    public function __construct(
        private readonly BattleUnitRepositoryStrategyInterface $unitRepository,
        private readonly WorldRepository $worldRepository,
    ) {
    }

    public function execute(int $worldId, int $actorUnitId, int $targetUnitId): void
    {
        $world = $this->worldRepository->getById($worldId);
        $actorUnit = $this->unitRepository->getByIdAs($actorUnitId, Attackable::class);
        $targetUnit = $this->unitRepository->getByIdAs($targetUnitId, BattleUnit::class);

        if (!$targetUnit->isAlive()) {
            throw new NotAliveException();
        }

        $actorPosition = $world->getUnitPosition($actorUnitId);
        $targetPosition = $world->getUnitPosition($targetUnitId);
        $distance = $world->calculateDistanceInMovepoints($actorPosition, $targetPosition);

        if (!$actorUnit->getRange() < $distance) {
            throw new NotInRangeException();
        }

        $targetUnit->takeDamage($actorUnit->getDamage());
        $this->unitRepository->save($targetUnit);

        if (!$targetUnit->isAlive()) {
            $world->removeUnit($targetUnitId);
            $this->worldRepository->save($world);
        }
    }
}

