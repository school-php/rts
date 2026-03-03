<?php

declare(strict_types=1);

namespace App\Infrastructure\Battle;

use App\Domain\Battle\BattleUnitInterface;
use App\Domain\Battle\Repository\BattleUnitRepositoryStrategyInterface;
use App\Infrastructure\Battle\Exception\UnitIncorrectTypeException;
use App\Infrastructure\Battle\Exception\UnitNotFoundException;
use PDO;

final class BattleUnitRepositoryStrategy implements BattleUnitRepositoryStrategyInterface
{
    public function __construct(
        private readonly PDO $db,
        private readonly UnitFactory $factory,
    ) {
    }

    public function getByIdAs(int $unitId, string $type): BattleUnitInterface
    {
        $stmt = $this->db->prepare('SELECT * FROM battle_units WHERE id = ?');
        $stmt->execute([$unitId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            throw new UnitNotFoundException($unitId);
        }

        $unit = $this->factory->create($row);

        if (!$unit instanceof $type) {
            throw new UnitIncorrectTypeException($unitId, $type);
        }

        return $unit;
    }

    public function save(BattleUnitInterface $unit): void
    {
        // TODO: persist unit
    }
}

