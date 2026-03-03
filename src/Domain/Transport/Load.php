<?php

declare(strict_types=1);

namespace App\Domain\Transport;

use App\Domain\Transport\Exception\OverloadException;

final class Load
{
    /** @var CargoItem[] */
    private array $items;

    /**
     * @param CargoItem[] $items
     */
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
                    fn (CargoItem $i) => $i->id() !== $itemId,
                ),
            ),
        );
    }

    public function totalWeight(): int
    {
        return array_sum(
            array_map(
                fn (CargoItem $i) => $i->weight(),
                $this->items,
            ),
        );
    }

    /**
     * @return CargoItem[]
     */
    public function items(): array
    {
        return $this->items;
    }
}

