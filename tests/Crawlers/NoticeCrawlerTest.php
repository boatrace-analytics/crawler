<?php

namespace Boatrace\Analytics\Tests\Crawlers;

use Goutte\Client as Goutte;
use Boatrace\Analytics\Crawlers\NoticeCrawler;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

/**
 * @author shimomo
 */
class NoticeCrawlerTest extends PHPUnitTestCase
{
    /**
     * @var \Boatrace\Analytics\NoticeCrawler
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
        $this->crawler = new NoticeCrawler(new Goutte);
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
        $this->assertSame(7, $response['stadiums'][24]['races'][1]['wind']);
        $this->assertSame(6, $response['stadiums'][24]['races'][1]['wave']);
        $this->assertSame(2, $response['stadiums'][24]['races'][1]['weather_id']);
        $this->assertSame(13.0, $response['stadiums'][24]['races'][1]['temperature']);
        $this->assertSame(14.0, $response['stadiums'][24]['races'][1]['water_temperature']);
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
        $this->assertSame(0.15, $response['stadiums'][24]['races'][1]['courses'][1]['start_timing']);
        $this->assertSame(0.22, $response['stadiums'][24]['races'][1]['courses'][2]['start_timing']);
        $this->assertSame(0.19, $response['stadiums'][24]['races'][1]['courses'][3]['start_timing']);
        $this->assertSame(0.18, $response['stadiums'][24]['races'][1]['courses'][4]['start_timing']);
        $this->assertSame(0.05, $response['stadiums'][24]['races'][1]['courses'][5]['start_timing']);
        $this->assertSame(0.11, $response['stadiums'][24]['races'][1]['courses'][6]['start_timing']);
        $this->assertSame(1, $response['stadiums'][24]['races'][1]['racers'][1]['bracket']);
        $this->assertSame(2, $response['stadiums'][24]['races'][1]['racers'][2]['bracket']);
        $this->assertSame(3, $response['stadiums'][24]['races'][1]['racers'][3]['bracket']);
        $this->assertSame(4, $response['stadiums'][24]['races'][1]['racers'][4]['bracket']);
        $this->assertSame(5, $response['stadiums'][24]['races'][1]['racers'][5]['bracket']);
        $this->assertSame(6, $response['stadiums'][24]['races'][1]['racers'][6]['bracket']);
        $this->assertSame(54.0, $response['stadiums'][24]['races'][1]['racers'][1]['weight']);
        $this->assertSame(54.2, $response['stadiums'][24]['races'][1]['racers'][2]['weight']);
        $this->assertSame(52.6, $response['stadiums'][24]['races'][1]['racers'][3]['weight']);
        $this->assertSame(51.2, $response['stadiums'][24]['races'][1]['racers'][4]['weight']);
        $this->assertSame(51.6, $response['stadiums'][24]['races'][1]['racers'][5]['weight']);
        $this->assertSame(47.5, $response['stadiums'][24]['races'][1]['racers'][6]['weight']);
        $this->assertSame(0.0, $response['stadiums'][24]['races'][1]['racers'][1]['weight_adjustment']);
        $this->assertSame(0.0, $response['stadiums'][24]['races'][1]['racers'][2]['weight_adjustment']);
        $this->assertSame(0.0, $response['stadiums'][24]['races'][1]['racers'][3]['weight_adjustment']);
        $this->assertSame(0.0, $response['stadiums'][24]['races'][1]['racers'][4]['weight_adjustment']);
        $this->assertSame(0.0, $response['stadiums'][24]['races'][1]['racers'][5]['weight_adjustment']);
        $this->assertSame(0.0, $response['stadiums'][24]['races'][1]['racers'][6]['weight_adjustment']);
        $this->assertSame(6.86, $response['stadiums'][24]['races'][1]['racers'][1]['exhibition_time']);
        $this->assertSame(6.89, $response['stadiums'][24]['races'][1]['racers'][2]['exhibition_time']);
        $this->assertSame(6.88, $response['stadiums'][24]['races'][1]['racers'][3]['exhibition_time']);
        $this->assertSame(6.80, $response['stadiums'][24]['races'][1]['racers'][4]['exhibition_time']);
        $this->assertSame(6.81, $response['stadiums'][24]['races'][1]['racers'][5]['exhibition_time']);
        $this->assertSame(6.76, $response['stadiums'][24]['races'][1]['racers'][6]['exhibition_time']);
        $this->assertSame(-0.5, $response['stadiums'][24]['races'][1]['racers'][1]['tilt_adjustment']);
        $this->assertSame(-0.5, $response['stadiums'][24]['races'][1]['racers'][2]['tilt_adjustment']);
        $this->assertSame(-0.5, $response['stadiums'][24]['races'][1]['racers'][3]['tilt_adjustment']);
        $this->assertSame(-0.5, $response['stadiums'][24]['races'][1]['racers'][4]['tilt_adjustment']);
        $this->assertSame(-0.5, $response['stadiums'][24]['races'][1]['racers'][5]['tilt_adjustment']);
        $this->assertSame(-0.5, $response['stadiums'][24]['races'][1]['racers'][6]['tilt_adjustment']);
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
        $this->assertNull($response['stadiums'][2]['races'][1]['wind']);
        $this->assertNull($response['stadiums'][2]['races'][1]['wave']);
        $this->assertNull($response['stadiums'][2]['races'][1]['weather_id']);
        $this->assertNull($response['stadiums'][2]['races'][1]['temperature']);
        $this->assertNull($response['stadiums'][2]['races'][1]['water_temperature']);
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
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][1]['bracket']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][2]['bracket']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][3]['bracket']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][4]['bracket']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][5]['bracket']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][6]['bracket']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][1]['weight']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][2]['weight']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][3]['weight']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][4]['weight']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][5]['weight']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][6]['weight']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][1]['weight_adjustment']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][2]['weight_adjustment']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][3]['weight_adjustment']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][4]['weight_adjustment']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][5]['weight_adjustment']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][6]['weight_adjustment']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][1]['exhibition_time']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][2]['exhibition_time']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][3]['exhibition_time']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][4]['exhibition_time']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][5]['exhibition_time']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][6]['exhibition_time']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][1]['tilt_adjustment']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][2]['tilt_adjustment']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][3]['tilt_adjustment']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][4]['tilt_adjustment']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][5]['tilt_adjustment']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][6]['tilt_adjustment']);
    }
}
