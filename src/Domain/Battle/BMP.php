<?php

declare(strict_types=1);

namespace App\Domain\Battle;

use InvalidArgumentException;

final class BMP extends BattleUnit implements BattleUnitInterface, Mechanic
{
    private static int $maxHP = 15;

    private function __construct(
        private HP $hp,
    ) {
    }

    public static function create(): BattleUnitInterface
    {
        return new self(HP::Setup(self::$maxHP, self::$maxHP));
    }

    public static function restoreFromData(array $data): BattleUnitInterface
    {
        if (!isset($data['currentHP'])) {
            throw new InvalidArgumentException();
        }

        return new self(HP::Setup($data['currentHP'], self::$maxHP));
    }
}

