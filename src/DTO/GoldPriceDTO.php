<?php

namespace GoldPrice\DTO;

/**
 * Class GoldPriceDTO
 *
 * Data Transfer Object that holds gold price information and the last updated timestamp.
 */
class GoldPriceDTO
{
    /**
     * @var array<string, array{buy: float, sell: float}>
     */
    public array $prices;

    /**
     * @var string|null
     */
    public ?string $updatedAt;

    /**
     * GoldPriceDTO constructor.
     *
     * @param array<string, array{buy: float, sell: float}> $prices
     * @param string|null $updatedAt
     */
    public function __construct(array $prices, ?string $updatedAt = null)
    {
        $this->prices = $prices;
        $this->updatedAt = $updatedAt;
    }

    /**
     * Convert DTO data to array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'updatedAt' => $this->updatedAt,
            'prices' => $this->prices,
        ];
    }
}
