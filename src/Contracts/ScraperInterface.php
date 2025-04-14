<?php 

namespace GoldPrice\Contracts;

use GoldPrice\DTO\GoldPriceDTO;

/**
 * Interface ScraperInterface
 *
 * Defines the contract for HTML scrapers that extract gold price data.
 */
interface ScraperInterface
{
    /**
     * Parse raw HTML and return structured gold price data.
     *
     * @param string $html
     * @return GoldPriceDTO
     */
    public function extractPrices(string $html): GoldPriceDTO;
}
?>