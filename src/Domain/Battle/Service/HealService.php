<?php

declare(strict_types=1);

namespace App\Domain\Battle\Service;

use App\Domain\Battle\Medic;
use App\Domain\Battle\Organic;

final class HealService
{
    public function heal(Medic $medic, Organic $organicBattleUnit, int $healValue): void
    {
        $medic->heal();
        $organicBattleUnit->takeHeal($healValue);
    }
}

