<?php

namespace GoldPrice\Tests;

use PHPUnit\Framework\TestCase;
use GoldPrice\Parser\IntergoldScraper;
use GoldPrice\DTO\GoldPriceDTO;
use GoldPrice\Exceptions\HtmlStructureException;

class IntergoldScraperTest extends TestCase
{
    /**
     * ทดสอบว่า scraper สามารถดึงข้อมูลราคาทองจาก HTML ได้ถูกต้อง
     */
    public function testExtractPrices()
    {

        $html = file_get_contents('https://www.intergold.co.th/gold-price/#gold_price?type=hour');

        if ($html === false) {
            $this->fail('Failed to fetch HTML content from the website');
        }

        $scraper = new IntergoldScraper();
        $result = $scraper->extractPrices($html);

        // ตรวจสอบว่า GoldPriceDTO ไม่เป็น null
        $this->assertInstanceOf(GoldPriceDTO::class, $result);

        // ตรวจสอบว่าเรามีราคาทองที่ถูกต้อง
        $prices = $result->prices;
        $this->assertArrayHasKey('LBMA', $prices);
        $this->assertArrayHasKey('InterGold', $prices);

        // ตรวจสอบราคาซื้อขายในแต่ละประเภท
        $this->assertGreaterThan(0, $prices['LBMA']['buy']);
        $this->assertGreaterThan(0, $prices['LBMA']['sell']);
        $this->assertGreaterThan(0, $prices['InterGold']['buy']);
        $this->assertGreaterThan(0, $prices['InterGold']['sell']);
    }

    /**
     * ทดสอบ HtmlStructureException กรณีที่มี HTML ที่ไม่ตรงตามโครงสร้างที่คาดหวัง
     */
    public function testExtractPricesWithInvalidHtml()
    {
        $html = '<html><body>Invalid HTML structure</body></html>';

        $scraper = new IntergoldScraper();

        // scraper จะต้องโยน HtmlStructureException เมื่อ HTML ไม่ถูกต้อง
        $this->expectException(HtmlStructureException::class);

        // ทดสอบการดึงข้อมูลจาก HTML ที่ไม่ถูกต้อง
        $scraper->extractPrices($html);
    }
}
