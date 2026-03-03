<?php

declare(strict_types=1);

namespace App\Infrastructure\Battle;

use App\Infrastructure\Battle\Exception\PersistableNotFoundException;

final class UnitFactory
{
    /**
     * @param array<string, class-string<Persistable>> $mapping
     */
    public function __construct(
        private array $mapping,
    ) {
    }

    /**
     * @param array<string,mixed> $data
     */
    public function create(array $data): Persistable
    {
        $type = $data['type'] ?? throw new PersistableNotFoundException('Type missing in DB row');

        /** @var class-string<Persistable> $className */
        $className = $this->mapping[$type] ?? throw new PersistableNotFoundException("Unknown type: {$type}");

        return $className::fromDatabase($data);
    }
}

