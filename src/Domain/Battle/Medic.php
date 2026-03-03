<?php

declare(strict_types=1);

namespace App\Domain\Battle;

use InvalidArgumentException;

final class Medic extends BattleUnit implements BattleUnitInterface, HealOrganic
{
    private static int $maxHP = 15;

    private function __construct(
        private HP $hp,
    ) {
    }

    public static function create(): BattleUnitInterface
    {
        return new self(HP::create(self::$maxHP, self::$maxHP));
    }

    public static function restoreFromData(array $data): BattleUnitInterface
    {
        if (!isset($data['currentHP'])) {
            throw new InvalidArgumentException();
        }

        return new self(HP::create($data['currentHP'], self::$maxHP));
    }

    public function heal(): void
    {
    }
}

