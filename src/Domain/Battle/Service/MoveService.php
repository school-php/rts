<?php

declare(strict_types=1);

namespace App\Domain\Battle\Service;

use App\Domain\World\GameWorld2D;
use App\Domain\World\GameWorldPosition;

final class MoveService
{
    public function move(GameWorld2D $world, int $unitId, GameWorldPosition $newPosition): int
    {
        $unitPosition = $world->getUnitPosition($unitId);
        $movepoints = $world->calculateDistanceInMovepoints($unitPosition, $newPosition);
        $world->changeUnitPosition($unitId, $newPosition);

        return $movepoints;
    }
}

