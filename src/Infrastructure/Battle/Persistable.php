<?php

declare(strict_types=1);

namespace App\Infrastructure\Battle;

interface Persistable
{
    /**
     * @param array<string,mixed> $data
     */
    public static function fromDatabase(array $data): self;
}

