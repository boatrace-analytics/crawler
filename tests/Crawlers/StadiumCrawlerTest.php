<?php

namespace Boatrace\Analytics\Tests\Crawlers;

use Goutte\Client as Goutte;
use Boatrace\Analytics\Crawlers\StadiumCrawler;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

/**
 * @author shimomo
 */
class StadiumCrawlerTest extends PHPUnitTestCase
{
    /**
     * @var \Boatrace\Analytics\StadiumCrawler
     */
    protected $crawler;

    /**
     * @var int
     */
    protected $seconds = 1;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->crawler = new StadiumCrawler(new Goutte);
    }

    /**
     * @return void
     */
    public function testCrawlStadiumId(): void
    {
        $this->assertSame([4, 5, 6, 10, 15, 18, 23, 24], $this->crawler->crawlStadiumId('2017-03-31', $this->seconds));
    }

    /**
     * @return void
     */
    public function testCrawlStadiumName(): void
    {
        $this->assertSame(['平和島', '多摩川', '浜名湖', '三国', '丸亀', '徳山', '唐津', '大村'], $this->crawler->crawlStadiumName('2017-03-31', $this->seconds));
    }
}
