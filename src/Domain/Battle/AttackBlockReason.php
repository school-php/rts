<?php

declare(strict_types=1);

namespace App\Domain\Battle;

enum AttackBlockReason: string
{
    case IN_CONTAINER = 'in_container';
    case INVULNERABLE = 'invulnerable';
}

