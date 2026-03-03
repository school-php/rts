<?php

declare(strict_types=1);

namespace App\Domain\Battle;

interface BattleUnitInterface
{
    public static function create(): BattleUnitInterface;

    public static function restoreFromData(array $data): BattleUnitInterface;
}

