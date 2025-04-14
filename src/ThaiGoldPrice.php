<?php 

namespace GoldPrice;

include_once 'vendor/autoload.php';

use GoldPrice\Http\GuzzleHttpClient;
use GoldPrice\Parser\IntergoldScraper;
use GoldPrice\Service\GoldPriceService;
use GoldPrice\DTO\GoldPriceDTO;

/**
 * Class ThaiGoldPrice
 *
 * A simple library to retrieve the latest gold prices from InterGold.
 */
class ThaiGoldPrice
{
    protected GoldPriceService $service;

    public function __construct()
    {
        $client = new GuzzleHttpClient();

        $scraper = new IntergoldScraper();
        $url = 'https://www.intergold.co.th/gold-price/#gold_price?type=hour';
        
        $this->service = new GoldPriceService($client, $scraper, $url);
    }

    /**
     * Get the most recent gold prices.
     *
     * @return GoldPriceDTO
     */
    public function getLastPrice():GoldPriceDTO
    {
        return $this->service->fetchGoldPrice();
    }
}

?>