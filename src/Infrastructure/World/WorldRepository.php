<?php

declare(strict_types=1);

namespace App\Infrastructure\World;

use App\Domain\World\GameWorld2D;

final class WorldRepository
{
    public function getById(int $id): GameWorld2D
    {
        $data = [
            'maxX' => 100,
            'maxY' => 100,
            'units' => [],
            'points' => [],
        ];

        return GameWorld2D::restoreFromData($data);
    }

    public function save(GameWorld2D $world): void
    {
        // TODO: persist world
    }
}

