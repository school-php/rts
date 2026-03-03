<?php

declare(strict_types=1);

namespace App\Application\Battle;

use App\Domain\Battle\Medic;
use App\Domain\Battle\Organic;
use App\Domain\Battle\Repository\BattleUnitRepositoryStrategyInterface;
use App\Domain\Battle\Service\HealService;

final class HealUseCase
{
    public function __construct(
        private readonly HealService $healService,
        private readonly BattleUnitRepositoryStrategyInterface $repository,
    ) {
    }

    public function execute(int $actorUnitId, int $targetUnitId, int $healValue): void
    {
        $actor = $this->repository->getByIdAs($actorUnitId, Medic::class);
        $target = $this->repository->getByIdAs($targetUnitId, Organic::class);

        $this->healService->heal($actor, $target, $healValue);
        $this->repository->save($actor);
        $this->repository->save($target);
    }
}

