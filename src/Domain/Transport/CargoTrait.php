<?php

declare(strict_types=1);

namespace App\Domain\Transport;

use App\Domain\Battle\AttackBlockReason;

trait CargoTrait
{
    private Cargo $cargo;

    public function canLoad(int $unitId, int $weight): bool
    {
        return $this->cargo->canLoadUnit($unitId, $weight);
    }

    public function load(Transportable $unit): void
    {
        $this->cargo->loadUnit($unit->getId(), $unit->getWeight());
        $unit->setContainerId($this->getContainerId());
        $unit->addAttackBlockReason(AttackBlockReason::IN_CONTAINER);
    }

    public function unload(Transportable $unit): void
    {
        $this->cargo->unloadUnit($unit->getId());
        $unit->setContainerId(0);
        $unit->removeAttackBlockReason(AttackBlockReason::IN_CONTAINER);
    }

    public function getCurrentLoad(): int
    {
        return $this->cargo->getCurrentLoad();
    }

    /** @return array<string,int> unitId => weight */
    public function getLoadedUnits(): array
    {
        return $this->cargo->getLoadedUnits();
    }
}

