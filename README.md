# thai-gold-price
A PHP library for fetching and parsing real-time gold prices from trusted sources.  
Built with Guzzle for HTTP requests and DOMDocument for robust and structured HTML parsing.

This library extracts both **buy** and **sell** prices, categorized by type, including:

- **LBMA** (London Bullion Market Association)
- **InterGold**
- **Thai Gold Traders Association**
- **Gold Spot**

## 🛠 Usage

Here's a simple example of how to use the SDK to fetch the latest gold prices:

```php
use GoldPrice\ThaiGoldPrice;
use GoldPrice\Exceptions\HtmlStructureException;
use GoldPrice\Exceptions\HttpRequestException;

try {
    $sdk = new ThaiGoldPrice();
    $lastPrice = $sdk->getLastPrice();
    print_r($lastPrice->toArray());

} catch (HttpRequestException $e) {
    echo "HTTP Request Error: " . $e->getMessage() . "\n";
} catch (HtmlStructureException $e) {
    echo "HTML Structure Error: " . $e->getMessage() . "\n";    
} catch (Exception $e) {
    echo "General Error: " . $e->getMessage() . "\n";    
}

### 🧾 Example Output

Calling `$sdk->getLastPrice()` will return a `GoldPriceDTO` object containing an array of prices grouped by type, along with the latest update timestamp.

```php
GoldPrice\DTO\GoldPriceDTO Object
(
    [prices] => Array
        (
            [LBMA] => Array
                (
                    [buy] => 52756
                    [sell] => 52796
                )

            [InterGold] => Array
                (
                    [buy] => 50903
                    [sell] => 50948
                )

            [Association] => Array
                (
                    [buy] => 51000
                    [sell] => 51100
                )

            [GoldSpot] => Array
                (
                    [buy] => 3206.73
                    [sell] => 3207.14
                )

            [USDTHB] => Array
                (
                    [buy] => 0
                    [sell] => 0
                )
        )

    [updatedAt] => 2025-04-14 22:16:58
)
