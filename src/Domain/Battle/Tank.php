<?php

declare(strict_types=1);

namespace App\Domain\Battle;

use InvalidArgumentException;

final class Tank extends BattleUnit implements BattleUnitInterface, Mechanic, Moveable, Attackable
{
    use AttackableTrait;

    private static int $maxHP = 15;
    private static int $maxFuel = 30;
    private static int $FuelPerMovepoint = 3;
    private static int $movepoints = 5;
    private static int $attackDamage = 5;
    private static int $attackRange = 7;

    private function __construct(
        private HP $hp,
        private Movement $movement,
        private Attack $attack,
    ) {
    }

    public static function create(): BattleUnitInterface
    {
        return new self(
            HP::create(self::$maxHP, self::$maxHP),
            Movement::create(self::$maxFuel, self::$maxFuel, self::$FuelPerMovepoint, self::$movepoints),
            Attack::create(self::$attackDamage, self::$attackRange),
        );
    }

    public static function restoreFromData(array $data): BattleUnitInterface
    {
        if (!isset($data['currentHP'], $data['currentFuel'])) {
            throw new InvalidArgumentException();
        }

        return new self(
            HP::create($data['currentHP'], self::$maxHP),
            Movement::create(self::$maxFuel, $data['currentFuel'], self::$FuelPerMovepoint, self::$movepoints),
            Attack::create(self::$attackDamage, self::$attackRange),
        );
    }

    public function getMovepoints(): int
    {
        return $this->movement->getAllowMovepoints();
    }

    public function move(int $movepoints): void
    {
        $this->movement = $this->movement->move($movepoints);
    }
}

