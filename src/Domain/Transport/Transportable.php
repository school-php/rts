<?php

declare(strict_types=1);

namespace App\Domain\Transport;

interface Transportable
{
    public function getId(): string;

    public function getWeight(): int;

    public function setContainerId(int $containerId): void;

    public function getContainerId(): int;

    public function isInContainer(): bool;
}

