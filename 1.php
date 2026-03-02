<?php

declare(strict_types=1);

interface BattleUnitInterface
{
    public static function create(): BattleUnitInterface;
    public static function restoreFromData(array $data): BattleUnitInterface;
}

interface Mechanic {}
interface Organic {}
interface HealOrganic
{
    public function heal(): void;
}

interface Moveable
{
    public function getMovepoints(): int;
    public function move(int $movepoints): void;
}

interface
{

}

readonly class Movement
{
    private function __construct(
        private int $maxFuel,
        private int $currentFuel,
        private int $fuelPerMovepoint,
        private int $movepoints,
    ) {}

    public static function create(int $maxFuel, int $currentFuel, int $fuelPerMovepoint, int $movepoints): Movement
    {
        return new self($maxFuel, $currentFuel, $fuelPerMovepoint, $movepoints);
    }

    public function getAllowMovepoints(): int
    {
        if ($this->movepoints * $this->fuelPerMovepoint > $this->currentFuel) {
            return $this->movepoints;
        } else {
            return (int)($this->currentFuel / $this->fuelPerMovepoint);
        }
    }

    public function move(int $movepoints): Movement
    {
        if ($movepoints > $this->getAllowMovepoints()) {
            throw new NoAllowMovepointsException();
        }

        $newMovepoints = $this->movepoints - $movepoints;
        $currentFuel = $this->currentFuel - ($movepoints * $this->fuelPerMovepoint);

        return new $this($this->maxFuel, $currentFuel, $this->fuelPerMovepoint, $newMovepoints);
    }
}

abstract class BattleUnit
{
    private HP $hp;
    static private int $maxHP = 0;
    public function takeDamage(int $damageValue): void
    {
        $this->hp = $this->hp->increase($damageValue);
    }

    public function takeHeal(int $healValue): void
    {
        $this->hp = $this->hp->decrease($healValue);
    }

    public function getHP(): int
    {
        return $this->hp->getValue();
    }
}

final readonly class HP
{
    private function __construct(
        private int $current,
        private int $max
    ) {
        if ($current < 0 || $current > $max) {
            throw new InvalidArgumentException();
        }
    }

    public static function create(int $current, int $max): self
    {
        return new self($current, $max);
    }

    public function increase(int $hp): self
    {
        if (!$this->isAlive()) {
            throw new UnitIsNotAliveException();
        }

        return new self(
            min($this->current + $hp, $this->max),
            $this->max
        );
    }

    public function decrease(int $hp): self
    {
        return new self(
            max($this->current - $hp, 0),
            $this->max
        );
    }

    public function isAlive(): bool
    {
        return $this->current > 0;
    }

    public function getValue(): int
    {
        return $this->current;
    }
}

class Tank extends BattleUnit implements BattleUnitInterface, Mechanic, Moveable
{
    static private int $maxHP = 15;
    static private int $maxFuel = 30;
    static private int $FuelPerMovepoint = 3;
    static private int $movepoints = 5;
    private function __construct(
        private HP $hp,
        private Movement $movement,
    ) {}

    public static function create(): BattleUnitInterface
    {
        return new self(
            HP::create(self::$maxHP, self::$maxHP),
            Movement::create(self::$maxFuel, self::$maxFuel, self::$FuelPerMovepoint, self::$movepoints),
        );
    }

    public static function restoreFromData(array $data): BattleUnitInterface
    {
        if (!isset($data['currentHP']) || !isset($data['currentFuel'])) {
            throw new InvalidArgumentException();
        }
        return new self(
            HP::create($data['currentHP'], self::$maxHP),
            Movement::create(self::$maxFuel, $data['currentFuel'], self::$FuelPerMovepoint, self::$movepoints),
        );
    }

    public function getMovepoints(): int
    {
        return $this->movement->getAllowMovepoints();
    }

    public function move(int $movepoints): void
    {
        $this->movement = $this->movement->move($movepoints);
    }
}

class Solder extends BattleUnit implements BattleUnitInterface, Organic
{
    static private int $maxHP = 15;
    private function __construct(
        private HP $hp,
    ) {}
    public static function create(): BattleUnitInterface
    {
        return new self(HP::Setup(self::$maxHP, self::$maxHP));
    }
    public static function restoreFromData(array $data): BattleUnitInterface
    {
        if (!isset($data['currentHP'])) {
            throw new InvalidArgumentException();
        }
        return new self(HP::Setup($data['currentHP'], self::$maxHP));
    }
}

class Medic extends BattleUnit implements BattleUnitInterface, HealOrganic
{
    static private int $maxHP = 15;
    private function __construct(
        private HP $hp,
    ) {}
    public static function create(): BattleUnitInterface
    {
        return new self(HP::create(self::$maxHP, self::$maxHP));
    }
    public static function restoreFromData(array $data): BattleUnitInterface
    {
        if (!isset($data['currentHP'])) {
            throw new InvalidArgumentException();
        }
        return new self(HP::create($data['currentHP'], self::$maxHP));
    }

    public function heal(): void
    {

    }
}

class BMP extends BattleUnit implements BattleUnitInterface, Mechanic
{
    static private int $maxHP = 15;
    private function __construct(
        private HP $hp,
    ) {}
    public static function create(): BattleUnitInterface
    {
        return new self(HP::Setup(self::$maxHP, self::$maxHP));
    }
    public static function restoreFromData(array $data): BattleUnitInterface
    {
        if (!isset($data['currentHP'])) {
            throw new InvalidArgumentException();
        }
        return new self(HP::Setup($data['currentHP'], self::$maxHP));
    }
}


class GameWorldPosition
{
    private function __construct(
        private int $x,
        private int $y,
    ) {}

    public static function create(int $x, int $y): GameWorldPosition
    {
        return new self($x, $y);
    }

    public function getX(): int
    {
        return $this->x;
    }

    public function getY(): int
    {
        return $this->y;
    }
}

class GameWorld2D
{
    private function __construct(
        private int $maxX,
        private int $maxY,
        private array $units,
        private array $points,
    ) {}

    public static function create($maxX = 100, $maxY = 100): GameWorld2D
    {
        return new self($maxX, $maxY, [], []);
    }

    public static function restoreFromData(array $data): GameWorld2D
    {
        if (!isset($data['maxX'], $data['maxY'], $data['units'], $data['points'])
            || !is_array($data['units']) || !is_array($data['points']))
        {
            throw new InvalidArgumentException();
        }

        return new self($data['maxX'], $data['maxY'], $data['units'], $data['points']);
    }

    public function addUnit(int $unitId, GameWorldPosition $position): void
    {
        $this->units[$unitId] = $position;
        $this->points[$position->getX()][$position->getY()] = $unitId;
    }

    public function getUnitPosition(int $unitId): GameWorldPosition
    {
        return $this->units[$unitId];
    }

    public function changeUnitPosition(int $unitId, GameWorldPosition $newPosition): void
    {
        $oldPosition = $this->getUnitPosition($unitId);
        unlink($this->points[$oldPosition->getX()][$oldPosition->getY()]);
        $this->units[$unitId] = $newPosition;
        $this->points[$newPosition->getX()][$newPosition->getY()] = $unitId;
    }

    public function calculateDistanceInMovepoints(GameWorldPosition $start, GameWorldPosition $end): int
    {
        if ($end->getX() > $this->maxX || $end->getY() > $this->maxY) {
            throw new InvalidWorldPositionException();
        }

        $dx = $this->calculateDifference($start->getX(), $end->getX());
        $dy = $this->calculateDifference($start->getX(), $end->getX());
        return (int)(min($dx, $dy) * 1.414 + $this->calculateDifference($dx, $dy));
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

// MOVE: Application Layer
class MoveUseCase
{
    public function __construct(
        private readonly BattleUnitRepositoryStrategy $unitRepository,
        private readonly WorldRepository $worldRepository,
    ) {}

    public function execute(int $worldId, int $unitId, GameWorldPosition $newPosition): void
    {
        $world = $this->worldRepository->getById($worldId);
        if ($world->isActive()) {
            throw new NotActiveWorldExpection();
        }

        $unitPosition = $world->getUnitPosition($unitId);
        $movepoints = $world->calculateDistanceInMovepoints($unitPosition, $newPosition);
        $unit = $this->unitRepository->getByIdAs($unitId, Moveable::class);
        $unit->move($movepoints);
        $world->changeUnitPosition($unitId, $newPosition);
        $this->unitRepository->save($unit);
        $this->worldRepository->save($world);
    }
}

// HEAL: Application Layer
class HealUseCase
{
    public function __construct(
        private readonly HealService $healService,
        private readonly BattleUnitRepositoryStrategy $repository,
    ) {}

    public function execute(int $actorUnitId, int $targetUnitId, int $healValue): void
    {
        $actor = $this->repository->getByIdAs($actorUnitId, Medic::class);
        $target = $this->repository->getByIdAs($targetUnitId, Organic::class);

        $this->healService->heal($actor, $target, $healValue);
        $this->repository->save($actor);
        $this->repository->save($target);
    }
}

// HEAL: Domain Service
class HealService
{
    public function heal(Medic $medic, Organic $organicBattleUnit, int $healValue): void
    {
        $medic->heal();
        $organicBattleUnit->takeHeal($healValue);
    }
}

interface IBattleUnitRepositoryStrategy
{
    /**
     * @template T of BattleUnitInterface
     * @param int $id
     * @param class-string<T> $type
     * @return T
     */
    public function getByIdAs(int $id, string $type): BattleUnitInterface;
}

class BattleUnitRepositoryStrategy implements IBattleUnitRepositoryStrategy
{
    public function getByIdAs(int $unitId, string $type): BattleUnitInterface
    {
        $stmt = $this->db->prepare("SELECT * FROM battle_units WHERE id = ?");
        $stmt->execute([$unitId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            throw new UnitNotFoundException($unitId);
        }

        // 5. Делегируем сборку объекта нашей фабрике
        // Она использует $row['type'] и метод ::fromDatabase()
        $unit = $this->factory->create($row);

        if (!$unit instanceof $type) {
            throw new UnitIncorrectTypeException($unitId, $type);
        }

        return $unit;
    }


    public function save(BattleUnitInterface $unit): void
    {
        echo "INSERT TO MYSQL";
    }
}

class WorldRepository
{
    public function getById(int $id): GameWorld2D
    {
        $data = [];
        return GameWorld2D::restoreFromData($data);
    }

    public function save(GameWorld2D $world): void
    {

    }
}

class UnitFactory
{
    public function __construct(
        private array $mapping // Наш внешний конфиг
    ) {}

    public function create(array $data): Unit
    {
        $type = $data['type'] ?? throw new \Exception("Type missing in DB row");

        /** @var class-string<Persistable> $className */
        $className = $this->mapping[$type] ?? throw new \Exception("Unknown type: $type");

        // Вызываем статический метод самого класса
        return $className::fromDatabase($data);
    }
}


readonly class CargoManifest
{
    /** @param array<int, int> $passengerWeights [unitId => weight] */
    public function __construct(
        private int $maxCapacity,
        private array $passengerWeights = []
    ) {}

    public function add(int $unitId, int $weight): self
    {
        if ($this->getCurrentWeight() + $weight > $this->maxCapacity) {
            throw new \DomainException("Недостаточно места в транспорте");
        }

        $weights = $this->passengerWeights;
        $weights[$unitId] = $weight;

        return new self($this->maxCapacity, $weights);
    }

    public function remove(int $unitId): self
    {
        $weights = $this->passengerWeights;
        unset($weights[$unitId]);

        return new self($this->maxCapacity, $weights);
    }

    public function getCurrentWeight(): int
    {
        return array_sum($this->passengerWeights);
    }

    public function has(int $unitId): bool
    {
        return isset($this->passengerWeights[$unitId]);
    }

    public function getIds(): array
    {
        return array_keys($this->passengerWeights);
    }
}

final readonly class CargoItem
{
    public function __construct(
        private int $itemId,
        private int $weight
    ) {
        if ($weight <= 0) {
            throw new InvalidArgumentException('Weight must be positive');
        }
    }

    public function id(): int
    {
        return $this->itemId;
    }

    public function weight(): int
    {
        return $this->weight;
    }

    public function equals(self $other): bool
    {
        return $this->itemId === $other->itemId;
    }
}

final class Load
{
    /** @var CargoItem[] */
    private array $items;

    private function __construct(array $items)
    {
        $this->items = $items;
    }

    public static function empty(): self
    {
        return new self([]);
    }

    public function add(CargoItem $item, int $maxWeight): self
    {
        $newWeight = $this->totalWeight() + $item->weight();

        if ($newWeight > $maxWeight) {
            throw new OverloadException();
        }

        return new self([...$this->items, $item]);
    }

    public function remove(int $itemId): self
    {
        return new self(
            array_values(
                array_filter(
                    $this->items,
                    fn (CargoItem $i) => $i->id() !== $itemId
                )
            )
        );
    }

    public function totalWeight(): int
    {
        return array_sum(
            array_map(fn (CargoItem $i) => $i->weight(), $this->items)
        );
    }

    /** @return CargoItem[] */
    public function items(): array
    {
        return $this->items;
    }
}
