<?php

declare(strict_types=1);

namespace App\Domain\Battle;

use App\Domain\Battle\Exception\AttackableBlockException;
use App\Domain\Battle\Exception\NotAliveException;

abstract class BattleUnit
{
    /** @var AttackBlockReason[] */
    private array $attackableBlockReasons = [];

    private HP $hp;

    private static int $maxHP = 0;

    public function takeDamage(int $damageValue): void
    {
        if (count($this->attackableBlockReasons) > 0) {
            throw new AttackableBlockException();
        }

        if (!$this->isAlive()) {
            throw new NotAliveException();
        }

        $this->hp = $this->hp->decrease($damageValue);
    }

    public function takeHeal(int $healValue): void
    {
        if (!$this->isAlive()) {
            throw new NotAliveException();
        }

        $this->hp = $this->hp->increase($healValue);
    }

    public function getHP(): int
    {
        return $this->hp->getValue();
    }

    public function isAlive(): bool
    {
        return $this->hp->isAlive();
    }

    public function addAttackableBlockReason(AttackBlockReason $reason): void
    {
        $this->attackableBlockReasons[$reason->name] = $reason;
    }

    public function removeAttackableBlockReason(AttackBlockReason $reason): void
    {
        unset($this->attackableBlockReasons[$reason->name]);
    }
}

