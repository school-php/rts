<?php

declare(strict_types=1);

namespace App\Domain\Battle;

interface Moveable
{
    public function getMovepoints(): int;

    public function move(int $movepoints): void;
}

