<?php 

namespace GoldPrice\Service;

use GoldPrice\Contracts\HttpClientInterface;
use GoldPrice\Contracts\ScraperInterface;
use GoldPrice\DTO\GoldPriceDTO;

/**
 * Class GoldPriceService
 *
 * Combines an HTTP client and scraper to fetch and parse thai gold price data.
 */
class GoldPriceService
{
    /**
     * @var HttpClientInterface
     */
    protected $client;

    /**
     * @var ScraperInterface
     */
    protected $scraper;

    /**
     * @var string
     */
    protected $url;

    /**
     * GoldPriceService constructor.
     *
     * @param HttpClientInterface $client
     * @param ScraperInterface $scraper
     * @param string $url
     */
    public function __construct(
        HttpClientInterface $client,
        ScraperInterface $scraper,
        string $url = ''
    ) {
        $this->client = $client;
        $this->scraper = $scraper;
        $this->url = $url;
    }

    /**
     * Fetch and parse gold price data from the configured URL.
     *
     * @return GoldPriceDTO
     */
    public function fetchGoldPrice(): GoldPriceDTO
    {
        $html = $this->client->get($this->url);
        return $this->scraper->extractPrices($html);
    }
}

?>
