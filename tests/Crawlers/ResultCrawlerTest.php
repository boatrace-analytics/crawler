<?php

namespace Boatrace\Analytics\Tests\Crawlers;

use Goutte\Client as Goutte;
use Boatrace\Analytics\Crawlers\ResultCrawler;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

/**
 * @author shimomo
 */
class ResultCrawlerTest extends PHPUnitTestCase
{
    /**
     * @var \Boatrace\Analytics\ResultCrawler
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
        $this->crawler = new ResultCrawler(new Goutte);
    }

    /**
     * @return void
     */
    public function testCrawl(): void
    {
        $response = $this->crawler->crawl([], '2017-03-31', 24, 1, $this->seconds);
        $this->assertSame('2017-03-31', $response['stadiums'][24]['races'][1]['date']);
        $this->assertSame(24, $response['stadiums'][24]['races'][1]['stadium_id']);
        $this->assertSame(1, $response['stadiums'][24]['races'][1]['race_number']);
        $this->assertSame(1, $response['stadiums'][24]['races'][1]['technique_id']);
        $this->assertSame(5, $response['stadiums'][24]['races'][1]['wind']);
        $this->assertSame(4, $response['stadiums'][24]['races'][1]['wave']);
        $this->assertSame(3, $response['stadiums'][24]['races'][1]['weather_id']);
        $this->assertSame(13.0, $response['stadiums'][24]['races'][1]['temperature']);
        $this->assertSame(14.0, $response['stadiums'][24]['races'][1]['water_temperature']);
        $this->assertSame(1, $response['stadiums'][24]['races'][1]['places'][1]['place']);
        $this->assertSame(2, $response['stadiums'][24]['races'][1]['places'][2]['place']);
        $this->assertSame(3, $response['stadiums'][24]['races'][1]['places'][3]['place']);
        $this->assertSame(4, $response['stadiums'][24]['races'][1]['places'][4]['place']);
        $this->assertSame(5, $response['stadiums'][24]['races'][1]['places'][5]['place']);
        $this->assertSame(6, $response['stadiums'][24]['races'][1]['places'][6]['place']);
        $this->assertSame(1, $response['stadiums'][24]['races'][1]['places'][1]['bracket']);
        $this->assertSame(2, $response['stadiums'][24]['races'][1]['places'][2]['bracket']);
        $this->assertSame(5, $response['stadiums'][24]['races'][1]['places'][3]['bracket']);
        $this->assertSame(4, $response['stadiums'][24]['races'][1]['places'][4]['bracket']);
        $this->assertSame(3, $response['stadiums'][24]['races'][1]['places'][5]['bracket']);
        $this->assertSame(6, $response['stadiums'][24]['races'][1]['places'][6]['bracket']);
        $this->assertSame(3833, $response['stadiums'][24]['races'][1]['places'][1]['racer_number']);
        $this->assertSame(3773, $response['stadiums'][24]['races'][1]['places'][2]['racer_number']);
        $this->assertSame(3800, $response['stadiums'][24]['races'][1]['places'][3]['racer_number']);
        $this->assertSame(4574, $response['stadiums'][24]['races'][1]['places'][4]['racer_number']);
        $this->assertSame(3471, $response['stadiums'][24]['races'][1]['places'][5]['racer_number']);
        $this->assertSame(4924, $response['stadiums'][24]['races'][1]['places'][6]['racer_number']);
        $this->assertSame('中辻 博訓', $response['stadiums'][24]['races'][1]['places'][1]['racer_name']);
        $this->assertSame('津留 浩一郎', $response['stadiums'][24]['races'][1]['places'][2]['racer_name']);
        $this->assertSame('牧 宏次', $response['stadiums'][24]['races'][1]['places'][3]['racer_name']);
        $this->assertSame('東 潤樹', $response['stadiums'][24]['races'][1]['places'][4]['racer_name']);
        $this->assertSame('赤峰 和也', $response['stadiums'][24]['races'][1]['places'][5]['racer_name']);
        $this->assertSame('中北 涼', $response['stadiums'][24]['races'][1]['places'][6]['racer_name']);
        $this->assertSame(1, $response['stadiums'][24]['races'][1]['courses'][1]['course']);
        $this->assertSame(2, $response['stadiums'][24]['races'][1]['courses'][2]['course']);
        $this->assertSame(3, $response['stadiums'][24]['races'][1]['courses'][3]['course']);
        $this->assertSame(4, $response['stadiums'][24]['races'][1]['courses'][4]['course']);
        $this->assertSame(5, $response['stadiums'][24]['races'][1]['courses'][5]['course']);
        $this->assertSame(6, $response['stadiums'][24]['races'][1]['courses'][6]['course']);
        $this->assertSame(1, $response['stadiums'][24]['races'][1]['courses'][1]['bracket']);
        $this->assertSame(2, $response['stadiums'][24]['races'][1]['courses'][2]['bracket']);
        $this->assertSame(3, $response['stadiums'][24]['races'][1]['courses'][3]['bracket']);
        $this->assertSame(4, $response['stadiums'][24]['races'][1]['courses'][4]['bracket']);
        $this->assertSame(5, $response['stadiums'][24]['races'][1]['courses'][5]['bracket']);
        $this->assertSame(6, $response['stadiums'][24]['races'][1]['courses'][6]['bracket']);
        $this->assertSame(0.25, $response['stadiums'][24]['races'][1]['courses'][1]['start_timing']);
        $this->assertSame(0.28, $response['stadiums'][24]['races'][1]['courses'][2]['start_timing']);
        $this->assertSame(0.31, $response['stadiums'][24]['races'][1]['courses'][3]['start_timing']);
        $this->assertSame(0.31, $response['stadiums'][24]['races'][1]['courses'][4]['start_timing']);
        $this->assertSame(0.23, $response['stadiums'][24]['races'][1]['courses'][5]['start_timing']);
        $this->assertSame(0.24, $response['stadiums'][24]['races'][1]['courses'][6]['start_timing']);
    }

    /**
     * @return void
     */
    public function testCrawlInCaseOfCancellation(): void
    {
        $response = $this->crawler->crawl([], '2019-10-14', 2, 1, $this->seconds);
        $this->assertSame('2019-10-14', $response['stadiums'][2]['races'][1]['date']);
        $this->assertSame(2, $response['stadiums'][2]['races'][1]['stadium_id']);
        $this->assertSame(1, $response['stadiums'][2]['races'][1]['race_number']);
        $this->assertNull($response['stadiums'][2]['races'][1]['technique_id']);
        $this->assertNull($response['stadiums'][2]['races'][1]['wind']);
        $this->assertNull($response['stadiums'][2]['races'][1]['wave']);
        $this->assertNull($response['stadiums'][2]['races'][1]['weather_id']);
        $this->assertNull($response['stadiums'][2]['races'][1]['temperature']);
        $this->assertNull($response['stadiums'][2]['races'][1]['water_temperature']);
        $this->assertNull($response['stadiums'][2]['races'][1]['places'][1]['place']);
        $this->assertNull($response['stadiums'][2]['races'][1]['places'][2]['place']);
        $this->assertNull($response['stadiums'][2]['races'][1]['places'][3]['place']);
        $this->assertNull($response['stadiums'][2]['races'][1]['places'][4]['place']);
        $this->assertNull($response['stadiums'][2]['races'][1]['places'][5]['place']);
        $this->assertNull($response['stadiums'][2]['races'][1]['places'][6]['place']);
        $this->assertNull($response['stadiums'][2]['races'][1]['places'][1]['bracket']);
        $this->assertNull($response['stadiums'][2]['races'][1]['places'][2]['bracket']);
        $this->assertNull($response['stadiums'][2]['races'][1]['places'][3]['bracket']);
        $this->assertNull($response['stadiums'][2]['races'][1]['places'][4]['bracket']);
        $this->assertNull($response['stadiums'][2]['races'][1]['places'][5]['bracket']);
        $this->assertNull($response['stadiums'][2]['races'][1]['places'][6]['bracket']);
        $this->assertNull($response['stadiums'][2]['races'][1]['places'][1]['racer_number']);
        $this->assertNull($response['stadiums'][2]['races'][1]['places'][2]['racer_number']);
        $this->assertNull($response['stadiums'][2]['races'][1]['places'][3]['racer_number']);
        $this->assertNull($response['stadiums'][2]['races'][1]['places'][4]['racer_number']);
        $this->assertNull($response['stadiums'][2]['races'][1]['places'][5]['racer_number']);
        $this->assertNull($response['stadiums'][2]['races'][1]['places'][6]['racer_number']);
        $this->assertNull($response['stadiums'][2]['races'][1]['places'][1]['racer_name']);
        $this->assertNull($response['stadiums'][2]['races'][1]['places'][2]['racer_name']);
        $this->assertNull($response['stadiums'][2]['races'][1]['places'][3]['racer_name']);
        $this->assertNull($response['stadiums'][2]['races'][1]['places'][4]['racer_name']);
        $this->assertNull($response['stadiums'][2]['races'][1]['places'][5]['racer_name']);
        $this->assertNull($response['stadiums'][2]['races'][1]['places'][6]['racer_name']);
        $this->assertSame(1, $response['stadiums'][2]['races'][1]['courses'][1]['course']);
        $this->assertSame(2, $response['stadiums'][2]['races'][1]['courses'][2]['course']);
        $this->assertSame(3, $response['stadiums'][2]['races'][1]['courses'][3]['course']);
        $this->assertSame(4, $response['stadiums'][2]['races'][1]['courses'][4]['course']);
        $this->assertSame(5, $response['stadiums'][2]['races'][1]['courses'][5]['course']);
        $this->assertSame(6, $response['stadiums'][2]['races'][1]['courses'][6]['course']);
        $this->assertNull($response['stadiums'][2]['races'][1]['courses'][1]['bracket']);
        $this->assertNull($response['stadiums'][2]['races'][1]['courses'][2]['bracket']);
        $this->assertNull($response['stadiums'][2]['races'][1]['courses'][3]['bracket']);
        $this->assertNull($response['stadiums'][2]['races'][1]['courses'][4]['bracket']);
        $this->assertNull($response['stadiums'][2]['races'][1]['courses'][5]['bracket']);
        $this->assertNull($response['stadiums'][2]['races'][1]['courses'][6]['bracket']);
        $this->assertNull($response['stadiums'][2]['races'][1]['courses'][1]['start_timing']);
        $this->assertNull($response['stadiums'][2]['races'][1]['courses'][2]['start_timing']);
        $this->assertNull($response['stadiums'][2]['races'][1]['courses'][3]['start_timing']);
        $this->assertNull($response['stadiums'][2]['races'][1]['courses'][4]['start_timing']);
        $this->assertNull($response['stadiums'][2]['races'][1]['courses'][5]['start_timing']);
        $this->assertNull($response['stadiums'][2]['races'][1]['courses'][6]['start_timing']);
    }
}
