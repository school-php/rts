<?php

declare(strict_types=1);

namespace App\Domain\Transport;

interface Carrier
{
    public function canLoad(int $weight): bool;

    public function load(int $unitId, int $weight): void;

    public function unload(int $unitId): void;

    /** @return array<string,int> unitId => weight */
    public function getLoadedUnits(): array;
}

