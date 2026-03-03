<?php

declare(strict_types=1);

namespace App\Application\Battle;

use App\Domain\Battle\Moveable;
use App\Domain\Battle\Repository\BattleUnitRepositoryStrategyInterface;
use App\Domain\Battle\Service\MoveService;
use App\Domain\World\Exception\NotActiveWorldExpection;
use App\Domain\World\GameWorldPosition;
use App\Infrastructure\World\WorldRepository;

final class MoveUseCase
{
    public function __construct(
        private readonly BattleUnitRepositoryStrategyInterface $unitRepository,
        private readonly WorldRepository $worldRepository,
        private readonly MoveService $moveService,
    ) {
    }

    public function execute(int $worldId, int $unitId, GameWorldPosition $newPosition): void
    {
        $world = $this->worldRepository->getById($worldId);

        if (!$world->isActive()) {
            throw new NotActiveWorldExpection();
        }

        $unit = $this->unitRepository->getByIdAs($unitId, Moveable::class);

        $movepoints = $this->moveService->move($world, $unitId, $newPosition);
        $unit->move($movepoints);

        $this->unitRepository->save($unit);
        $this->worldRepository->save($world);
    }
}

