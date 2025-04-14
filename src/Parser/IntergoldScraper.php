<?php 
namespace GoldPrice\Parser;

use GoldPrice\Contracts\ScraperInterface;
use GoldPrice\DTO\GoldPriceDTO;
use GoldPrice\Exceptions\HtmlStructureException;
use DOMDocument;
use DOMXPath;

/**
 * Class IntergoldScraper
 *
 * Extracts gold prices from InterGold's website HTML content.
 */
class IntergoldScraper implements ScraperInterface
{
    public function extractPrices(string $html): GoldPriceDTO
    {
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $dom->loadHTML($html);
        $xpath = new DOMXPath($dom);

        // Map id ของแต่ละ row ที่ต้องการดึงข้อมูล พร้อมชื่อประเภท
        $types = [
            'LBMA' => 'trend-0',
            'InterGold' => 'trend-1',
            'Association' => 'trend-2',
            'GoldSpot' => 'trend-3',
            'USDTHB' => 'trend-4',
        ];

        $results = [];

        foreach ($types as $label => $rowId) {
            $buyXpath = "//tr[@id='$rowId']/td[contains(@class, 'buy')]//span[@class='price']";
            $sellXpath = "//tr[@id='$rowId']/td[contains(@class, 'sell')]//span[@class='price']";

            $buyNode = $xpath->query($buyXpath);
            $sellNode = $xpath->query($sellXpath);

            $buy = $this->parsePrice($buyNode->length > 0 ? $buyNode[0]->textContent : '');
            $sell = $this->parsePrice($sellNode->length > 0 ? $sellNode[0]->textContent : '');

            $results[$label] = [
                'buy' => $buy,
                'sell' => $sell,
            ];
        }

        // ค้นหาวันที่อัปเดตล่าสุด
        $updateTimeXpath = "//div[contains(@class, 'update-gold-price')]";
        $updateNode = $xpath->query($updateTimeXpath);

        $updatedAt = null;

        if ($updateNode->length > 0) {
            $rawDate = trim($updateNode[0]->textContent);
            $updatedAt = $this->convertThaiDateToIso($rawDate);
        }

        return new GoldPriceDTO($results,$updatedAt); // <-- ปรับ DTO ให้รองรับ array ของราคาประเภทต่าง ๆ
    }

    /**
     * Convert Thai date format to ISO 8601 format.
     *
     * @param string $thaiDate
     * @return string|null
     */
    private function convertThaiDateToIso(string $thaiDate): ?string
    {
        // ตัวอย่าง input: "14 เมษายน 2568 | 20:18:00"
        $parts = explode('|', $thaiDate);
        if (count($parts) !== 2) return null;
    
        $datePart = trim($parts[0]);
        $timePart = trim($parts[1]);
    
        // แปลงเดือนภาษาไทยเป็นตัวเลข
        $months = [
            'มกราคม' => '01', 'กุมภาพันธ์' => '02', 'มีนาคม' => '03',
            'เมษายน' => '04', 'พฤษภาคม' => '05', 'มิถุนายน' => '06',
            'กรกฎาคม' => '07', 'สิงหาคม' => '08', 'กันยายน' => '09',
            'ตุลาคม' => '10', 'พฤศจิกายน' => '11', 'ธันวาคม' => '12',
        ];
    
        if (!preg_match('/(\d{1,2}) (.+?) (\d{4})/', $datePart, $matches)) {
            return null;
        }
    
        [$full, $day, $thaiMonth, $thaiYear] = $matches;
    
        $month = $months[$thaiMonth] ?? null;
        if (!$month) return null;
    
        // แปลง พ.ศ. → ค.ศ.
        $year = intval($thaiYear) - 543;
    
        return sprintf('%04d-%02d-%02d %s', $year, $month, intval($day), $timePart);
    }

    /**
     * Parse a price string and convert to float.
     *
     * @param string $price
     * @return float
     */
    private function parsePrice(string $price): float
    {
        $price = trim($price);
        if ($price === '-' || $price === '') {
            return 0;
        }
        // เอา , ออกก่อนแปลงเป็น float
        return floatval(str_replace(',', '', $price));
    }
}
