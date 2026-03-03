<?php

declare(strict_types=1);

namespace App\Domain\World;

use App\Domain\World\Exception\InvalidWorldPositionException;

final class GameWorld2D
{
    private function __construct(
        private int $maxX,
        private int $maxY,
        /** @var array<int, GameWorldPosition> */
        private array $units,
        /** @var array<int, array<int, int>> */
        private array $points,
    ) {
    }

    public static function create(int $maxX = 100, int $maxY = 100): self
    {
        return new self($maxX, $maxY, [], []);
    }

    /**
     * @param array{
     *     maxX:int,
     *     maxY:int,
     *     units:array<int, GameWorldPosition>,
     *     points:array<int, array<int,int>>
     * } $data
     */
    public static function restoreFromData(array $data): self
    {
        if (
            !isset($data['maxX'], $data['maxY'], $data['units'], $data['points'])
            || !is_array($data['units'])
            || !is_array($data['points'])
        ) {
            throw new InvalidWorldPositionException();
        }

        return new self($data['maxX'], $data['maxY'], $data['units'], $data['points']);
    }

    public function addUnit(int $unitId, GameWorldPosition $position): void
    {
        $this->units[$unitId] = $position;
        $this->points[$position->getX()][$position->getY()] = $unitId;
    }

    public function removeUnit(int $unitId): void
    {
        $position = $this->units[$unitId];
        unset($this->points[$position->getX()][$position->getY()], $this->units[$unitId]);
    }

    public function getUnitPosition(int $unitId): GameWorldPosition
    {
        return $this->units[$unitId];
    }

    public function changeUnitPosition(int $unitId, GameWorldPosition $newPosition): void
    {
        $oldPosition = $this->getUnitPosition($unitId);
        unset($this->points[$oldPosition->getX()][$oldPosition->getY()]);

        $this->units[$unitId] = $newPosition;
        $this->points[$newPosition->getX()][$newPosition->getY()] = $unitId;
    }

    public function calculateDistanceInMovepoints(GameWorldPosition $start, GameWorldPosition $end): int
    {
        if ($end->getX() > $this->maxX || $end->getY() > $this->maxY) {
            throw new InvalidWorldPositionException();
        }

        $dx = $this->calculateDifference($start->getX(), $end->getX());
        $dy = $this->calculateDifference($start->getY(), $end->getY());

        return (int) (min($dx, $dy) * 1.414 + $this->calculateDifference($dx, $dy));
    }

    public function isActive(): bool
    {
        return true;
    }

    private function calculateDifference(int $x, int $y): int
    {
        return ($x > $y) ? $x - $y : $y - $x;
    }
}

