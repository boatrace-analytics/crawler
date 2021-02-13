<?php

namespace Boatrace\Analytics\Tests\Crawlers;

use Goutte\Client as Goutte;
use Boatrace\Analytics\Crawlers\ProgramCrawler;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

/**
 * @author shimomo
 */
class ProgramCrawlerTest extends PHPUnitTestCase
{
    /**
     * @var \Boatrace\Analytics\ProgramCrawler
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
        $this->crawler = new ProgramCrawler(new Goutte);
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
        $this->assertSame('おおむら桜祭り競走', $response['stadiums'][24]['races'][1]['title']);
        $this->assertSame('めざまし戦一般', $response['stadiums'][24]['races'][1]['subtitle']);
        $this->assertSame(1800, $response['stadiums'][24]['races'][1]['distance']);
        $this->assertSame('2017-03-31 12:00:00', $response['stadiums'][24]['races'][1]['closed_at']);
        $this->assertSame(1, $response['stadiums'][24]['races'][1]['racers'][1]['bracket']);
        $this->assertSame(2, $response['stadiums'][24]['races'][1]['racers'][2]['bracket']);
        $this->assertSame(3, $response['stadiums'][24]['races'][1]['racers'][3]['bracket']);
        $this->assertSame(4, $response['stadiums'][24]['races'][1]['racers'][4]['bracket']);
        $this->assertSame(5, $response['stadiums'][24]['races'][1]['racers'][5]['bracket']);
        $this->assertSame(6, $response['stadiums'][24]['races'][1]['racers'][6]['bracket']);
        $this->assertSame(3833, $response['stadiums'][24]['races'][1]['racers'][1]['number']);
        $this->assertSame(3773, $response['stadiums'][24]['races'][1]['racers'][2]['number']);
        $this->assertSame(3471, $response['stadiums'][24]['races'][1]['racers'][3]['number']);
        $this->assertSame(4574, $response['stadiums'][24]['races'][1]['racers'][4]['number']);
        $this->assertSame(3800, $response['stadiums'][24]['races'][1]['racers'][5]['number']);
        $this->assertSame(4924, $response['stadiums'][24]['races'][1]['racers'][6]['number']);
        $this->assertSame('中辻 博訓', $response['stadiums'][24]['races'][1]['racers'][1]['name']);
        $this->assertSame('津留 浩一郎', $response['stadiums'][24]['races'][1]['racers'][2]['name']);
        $this->assertSame('赤峰 和也', $response['stadiums'][24]['races'][1]['racers'][3]['name']);
        $this->assertSame('東 潤樹', $response['stadiums'][24]['races'][1]['racers'][4]['name']);
        $this->assertSame('牧 宏次', $response['stadiums'][24]['races'][1]['racers'][5]['name']);
        $this->assertSame('中北 涼', $response['stadiums'][24]['races'][1]['racers'][6]['name']);
        $this->assertSame(42, $response['stadiums'][24]['races'][1]['racers'][1]['age']);
        $this->assertSame(42, $response['stadiums'][24]['races'][1]['racers'][2]['age']);
        $this->assertSame(47, $response['stadiums'][24]['races'][1]['racers'][3]['age']);
        $this->assertSame(28, $response['stadiums'][24]['races'][1]['racers'][4]['age']);
        $this->assertSame(43, $response['stadiums'][24]['races'][1]['racers'][5]['age']);
        $this->assertSame(24, $response['stadiums'][24]['races'][1]['racers'][6]['age']);
        $this->assertSame(54.0, $response['stadiums'][24]['races'][1]['racers'][1]['weight']);
        $this->assertSame(54.2, $response['stadiums'][24]['races'][1]['racers'][2]['weight']);
        $this->assertSame(52.6, $response['stadiums'][24]['races'][1]['racers'][3]['weight']);
        $this->assertSame(51.2, $response['stadiums'][24]['races'][1]['racers'][4]['weight']);
        $this->assertSame(51.6, $response['stadiums'][24]['races'][1]['racers'][5]['weight']);
        $this->assertSame(47.5, $response['stadiums'][24]['races'][1]['racers'][6]['weight']);
        $this->assertSame(1, $response['stadiums'][24]['races'][1]['racers'][1]['class_id']);
        $this->assertSame(3, $response['stadiums'][24]['races'][1]['racers'][2]['class_id']);
        $this->assertSame(3, $response['stadiums'][24]['races'][1]['racers'][3]['class_id']);
        $this->assertSame(3, $response['stadiums'][24]['races'][1]['racers'][4]['class_id']);
        $this->assertSame(3, $response['stadiums'][24]['races'][1]['racers'][5]['class_id']);
        $this->assertSame(4, $response['stadiums'][24]['races'][1]['racers'][6]['class_id']);
        $this->assertSame(18, $response['stadiums'][24]['races'][1]['racers'][1]['branch_id']);
        $this->assertSame(42, $response['stadiums'][24]['races'][1]['racers'][2]['branch_id']);
        $this->assertSame(41, $response['stadiums'][24]['races'][1]['racers'][3]['branch_id']);
        $this->assertSame(34, $response['stadiums'][24]['races'][1]['racers'][4]['branch_id']);
        $this->assertSame(13, $response['stadiums'][24]['races'][1]['racers'][5]['branch_id']);
        $this->assertSame(42, $response['stadiums'][24]['races'][1]['racers'][6]['branch_id']);
        $this->assertSame(18, $response['stadiums'][24]['races'][1]['racers'][1]['birthplace_id']);
        $this->assertSame(42, $response['stadiums'][24]['races'][1]['racers'][2]['birthplace_id']);
        $this->assertSame(41, $response['stadiums'][24]['races'][1]['racers'][3]['birthplace_id']);
        $this->assertSame(38, $response['stadiums'][24]['races'][1]['racers'][4]['birthplace_id']);
        $this->assertSame(14, $response['stadiums'][24]['races'][1]['racers'][5]['birthplace_id']);
        $this->assertSame(23, $response['stadiums'][24]['races'][1]['racers'][6]['birthplace_id']);
        $this->assertSame(1, $response['stadiums'][24]['races'][1]['racers'][1]['flying_count']);
        $this->assertSame(0, $response['stadiums'][24]['races'][1]['racers'][2]['flying_count']);
        $this->assertSame(0, $response['stadiums'][24]['races'][1]['racers'][3]['flying_count']);
        $this->assertSame(1, $response['stadiums'][24]['races'][1]['racers'][4]['flying_count']);
        $this->assertSame(0, $response['stadiums'][24]['races'][1]['racers'][5]['flying_count']);
        $this->assertSame(1, $response['stadiums'][24]['races'][1]['racers'][6]['flying_count']);
        $this->assertSame(0, $response['stadiums'][24]['races'][1]['racers'][1]['late_count']);
        $this->assertSame(0, $response['stadiums'][24]['races'][1]['racers'][2]['late_count']);
        $this->assertSame(0, $response['stadiums'][24]['races'][1]['racers'][3]['late_count']);
        $this->assertSame(0, $response['stadiums'][24]['races'][1]['racers'][4]['late_count']);
        $this->assertSame(0, $response['stadiums'][24]['races'][1]['racers'][5]['late_count']);
        $this->assertSame(0, $response['stadiums'][24]['races'][1]['racers'][6]['late_count']);
        $this->assertSame(0.13, $response['stadiums'][24]['races'][1]['racers'][1]['start_timing']);
        $this->assertSame(0.18, $response['stadiums'][24]['races'][1]['racers'][2]['start_timing']);
        $this->assertSame(0.21, $response['stadiums'][24]['races'][1]['racers'][3]['start_timing']);
        $this->assertSame(0.17, $response['stadiums'][24]['races'][1]['racers'][4]['start_timing']);
        $this->assertSame(0.20, $response['stadiums'][24]['races'][1]['racers'][5]['start_timing']);
        $this->assertSame(0.18, $response['stadiums'][24]['races'][1]['racers'][6]['start_timing']);
        $this->assertSame(6.45, $response['stadiums'][24]['races'][1]['racers'][1]['national_1_percentages']);
        $this->assertSame(5.70, $response['stadiums'][24]['races'][1]['racers'][2]['national_1_percentages']);
        $this->assertSame(4.13, $response['stadiums'][24]['races'][1]['racers'][3]['national_1_percentages']);
        $this->assertSame(4.88, $response['stadiums'][24]['races'][1]['racers'][4]['national_1_percentages']);
        $this->assertSame(5.05, $response['stadiums'][24]['races'][1]['racers'][5]['national_1_percentages']);
        $this->assertSame(1.76, $response['stadiums'][24]['races'][1]['racers'][6]['national_1_percentages']);
        $this->assertSame(45.36, $response['stadiums'][24]['races'][1]['racers'][1]['national_2_percentages']);
        $this->assertSame(35.85, $response['stadiums'][24]['races'][1]['racers'][2]['national_2_percentages']);
        $this->assertSame(16.50, $response['stadiums'][24]['races'][1]['racers'][3]['national_2_percentages']);
        $this->assertSame(33.78, $response['stadiums'][24]['races'][1]['racers'][4]['national_2_percentages']);
        $this->assertSame(34.48, $response['stadiums'][24]['races'][1]['racers'][5]['national_2_percentages']);
        $this->assertSame(1.96, $response['stadiums'][24]['races'][1]['racers'][6]['national_2_percentages']);
        $this->assertSame(65.98, $response['stadiums'][24]['races'][1]['racers'][1]['national_3_percentages']);
        $this->assertSame(61.32, $response['stadiums'][24]['races'][1]['racers'][2]['national_3_percentages']);
        $this->assertSame(33.01, $response['stadiums'][24]['races'][1]['racers'][3]['national_3_percentages']);
        $this->assertSame(41.89, $response['stadiums'][24]['races'][1]['racers'][4]['national_3_percentages']);
        $this->assertSame(45.98, $response['stadiums'][24]['races'][1]['racers'][5]['national_3_percentages']);
        $this->assertSame(1.96, $response['stadiums'][24]['races'][1]['racers'][6]['national_3_percentages']);
        $this->assertSame(7.78, $response['stadiums'][24]['races'][1]['racers'][1]['local_1_percentages']);
        $this->assertSame(6.40, $response['stadiums'][24]['races'][1]['racers'][2]['local_1_percentages']);
        $this->assertSame(3.95, $response['stadiums'][24]['races'][1]['racers'][3]['local_1_percentages']);
        $this->assertSame(4.17, $response['stadiums'][24]['races'][1]['racers'][4]['local_1_percentages']);
        $this->assertSame(5.00, $response['stadiums'][24]['races'][1]['racers'][5]['local_1_percentages']);
        $this->assertSame(0.00, $response['stadiums'][24]['races'][1]['racers'][6]['local_1_percentages']);
        $this->assertSame(55.56, $response['stadiums'][24]['races'][1]['racers'][1]['local_2_percentages']);
        $this->assertSame(52.48, $response['stadiums'][24]['races'][1]['racers'][2]['local_2_percentages']);
        $this->assertSame(14.29, $response['stadiums'][24]['races'][1]['racers'][3]['local_2_percentages']);
        $this->assertSame(13.04, $response['stadiums'][24]['races'][1]['racers'][4]['local_2_percentages']);
        $this->assertSame(37.50, $response['stadiums'][24]['races'][1]['racers'][5]['local_2_percentages']);
        $this->assertSame(0.00, $response['stadiums'][24]['races'][1]['racers'][6]['local_2_percentages']);
        $this->assertSame(77.78, $response['stadiums'][24]['races'][1]['racers'][1]['local_3_percentages']);
        $this->assertSame(65.35, $response['stadiums'][24]['races'][1]['racers'][2]['local_3_percentages']);
        $this->assertSame(33.33, $response['stadiums'][24]['races'][1]['racers'][3]['local_3_percentages']);
        $this->assertSame(30.43, $response['stadiums'][24]['races'][1]['racers'][4]['local_3_percentages']);
        $this->assertSame(37.50, $response['stadiums'][24]['races'][1]['racers'][5]['local_3_percentages']);
        $this->assertSame(0.00, $response['stadiums'][24]['races'][1]['racers'][6]['local_3_percentages']);
        $this->assertSame(66, $response['stadiums'][24]['races'][1]['racers'][1]['motor_number']);
        $this->assertSame(43, $response['stadiums'][24]['races'][1]['racers'][2]['motor_number']);
        $this->assertSame(59, $response['stadiums'][24]['races'][1]['racers'][3]['motor_number']);
        $this->assertSame(73, $response['stadiums'][24]['races'][1]['racers'][4]['motor_number']);
        $this->assertSame(52, $response['stadiums'][24]['races'][1]['racers'][5]['motor_number']);
        $this->assertSame(64, $response['stadiums'][24]['races'][1]['racers'][6]['motor_number']);
        $this->assertSame(88.89, $response['stadiums'][24]['races'][1]['racers'][1]['motor_2_percentages']);
        $this->assertSame(25.00, $response['stadiums'][24]['races'][1]['racers'][2]['motor_2_percentages']);
        $this->assertSame(33.33, $response['stadiums'][24]['races'][1]['racers'][3]['motor_2_percentages']);
        $this->assertSame(37.50, $response['stadiums'][24]['races'][1]['racers'][4]['motor_2_percentages']);
        $this->assertSame(0.00, $response['stadiums'][24]['races'][1]['racers'][5]['motor_2_percentages']);
        $this->assertSame(66.67, $response['stadiums'][24]['races'][1]['racers'][6]['motor_2_percentages']);
        $this->assertSame(100.00, $response['stadiums'][24]['races'][1]['racers'][1]['motor_3_percentages']);
        $this->assertSame(37.50, $response['stadiums'][24]['races'][1]['racers'][2]['motor_3_percentages']);
        $this->assertSame(44.44, $response['stadiums'][24]['races'][1]['racers'][3]['motor_3_percentages']);
        $this->assertSame(50.00, $response['stadiums'][24]['races'][1]['racers'][4]['motor_3_percentages']);
        $this->assertSame(0.00, $response['stadiums'][24]['races'][1]['racers'][5]['motor_3_percentages']);
        $this->assertSame(88.89, $response['stadiums'][24]['races'][1]['racers'][6]['motor_3_percentages']);
        $this->assertSame(71, $response['stadiums'][24]['races'][1]['racers'][1]['boat_number']);
        $this->assertSame(41, $response['stadiums'][24]['races'][1]['racers'][2]['boat_number']);
        $this->assertSame(67, $response['stadiums'][24]['races'][1]['racers'][3]['boat_number']);
        $this->assertSame(34, $response['stadiums'][24]['races'][1]['racers'][4]['boat_number']);
        $this->assertSame(65, $response['stadiums'][24]['races'][1]['racers'][5]['boat_number']);
        $this->assertSame(46, $response['stadiums'][24]['races'][1]['racers'][6]['boat_number']);
        $this->assertSame(37.14, $response['stadiums'][24]['races'][1]['racers'][1]['boat_2_percentages']);
        $this->assertSame(28.87, $response['stadiums'][24]['races'][1]['racers'][2]['boat_2_percentages']);
        $this->assertSame(30.71, $response['stadiums'][24]['races'][1]['racers'][3]['boat_2_percentages']);
        $this->assertSame(27.86, $response['stadiums'][24]['races'][1]['racers'][4]['boat_2_percentages']);
        $this->assertSame(39.57, $response['stadiums'][24]['races'][1]['racers'][5]['boat_2_percentages']);
        $this->assertSame(24.82, $response['stadiums'][24]['races'][1]['racers'][6]['boat_2_percentages']);
        $this->assertSame(55.00, $response['stadiums'][24]['races'][1]['racers'][1]['boat_3_percentages']);
        $this->assertSame(40.14, $response['stadiums'][24]['races'][1]['racers'][2]['boat_3_percentages']);
        $this->assertSame(50.71, $response['stadiums'][24]['races'][1]['racers'][3]['boat_3_percentages']);
        $this->assertSame(42.14, $response['stadiums'][24]['races'][1]['racers'][4]['boat_3_percentages']);
        $this->assertSame(56.83, $response['stadiums'][24]['races'][1]['racers'][5]['boat_3_percentages']);
        $this->assertSame(42.55, $response['stadiums'][24]['races'][1]['racers'][6]['boat_3_percentages']);
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
        $this->assertNull($response['stadiums'][2]['races'][1]['title']);
        $this->assertNull($response['stadiums'][2]['races'][1]['subtitle']);
        $this->assertNull($response['stadiums'][2]['races'][1]['distance']);
        $this->assertNull($response['stadiums'][2]['races'][1]['closed_at']);
        $this->assertSame(1, $response['stadiums'][2]['races'][1]['racers'][1]['bracket']);
        $this->assertSame(2, $response['stadiums'][2]['races'][1]['racers'][2]['bracket']);
        $this->assertSame(3, $response['stadiums'][2]['races'][1]['racers'][3]['bracket']);
        $this->assertSame(4, $response['stadiums'][2]['races'][1]['racers'][4]['bracket']);
        $this->assertSame(5, $response['stadiums'][2]['races'][1]['racers'][5]['bracket']);
        $this->assertSame(6, $response['stadiums'][2]['races'][1]['racers'][6]['bracket']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][1]['number']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][2]['number']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][3]['number']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][4]['number']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][5]['number']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][6]['number']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][1]['name']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][2]['name']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][3]['name']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][4]['name']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][5]['name']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][6]['name']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][1]['age']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][2]['age']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][3]['age']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][4]['age']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][5]['age']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][6]['age']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][1]['weight']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][2]['weight']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][3]['weight']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][4]['weight']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][5]['weight']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][6]['weight']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][1]['class_id']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][2]['class_id']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][3]['class_id']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][4]['class_id']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][5]['class_id']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][6]['class_id']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][1]['branch_id']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][2]['branch_id']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][3]['branch_id']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][4]['branch_id']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][5]['branch_id']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][6]['branch_id']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][1]['birthplace_id']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][2]['birthplace_id']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][3]['birthplace_id']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][4]['birthplace_id']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][5]['birthplace_id']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][6]['birthplace_id']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][1]['flying_count']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][2]['flying_count']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][3]['flying_count']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][4]['flying_count']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][5]['flying_count']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][6]['flying_count']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][1]['late_count']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][2]['late_count']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][3]['late_count']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][4]['late_count']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][5]['late_count']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][6]['late_count']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][1]['start_timing']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][2]['start_timing']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][3]['start_timing']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][4]['start_timing']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][5]['start_timing']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][6]['start_timing']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][1]['national_1_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][2]['national_1_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][3]['national_1_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][4]['national_1_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][5]['national_1_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][6]['national_1_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][1]['national_2_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][2]['national_2_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][3]['national_2_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][4]['national_2_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][5]['national_2_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][6]['national_2_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][1]['national_3_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][2]['national_3_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][3]['national_3_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][4]['national_3_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][5]['national_3_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][6]['national_3_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][1]['local_1_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][2]['local_1_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][3]['local_1_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][4]['local_1_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][5]['local_1_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][6]['local_1_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][1]['local_2_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][2]['local_2_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][3]['local_2_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][4]['local_2_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][5]['local_2_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][6]['local_2_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][1]['local_3_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][2]['local_3_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][3]['local_3_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][4]['local_3_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][5]['local_3_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][6]['local_3_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][1]['motor_number']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][2]['motor_number']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][3]['motor_number']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][4]['motor_number']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][5]['motor_number']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][6]['motor_number']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][1]['motor_2_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][2]['motor_2_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][3]['motor_2_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][4]['motor_2_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][5]['motor_2_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][6]['motor_2_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][1]['motor_3_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][2]['motor_3_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][3]['motor_3_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][4]['motor_3_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][5]['motor_3_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][6]['motor_3_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][1]['boat_number']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][2]['boat_number']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][3]['boat_number']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][4]['boat_number']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][5]['boat_number']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][6]['boat_number']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][1]['boat_2_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][2]['boat_2_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][3]['boat_2_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][4]['boat_2_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][5]['boat_2_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][6]['boat_2_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][1]['boat_3_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][2]['boat_3_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][3]['boat_3_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][4]['boat_3_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][5]['boat_3_percentages']);
        $this->assertNull($response['stadiums'][2]['races'][1]['racers'][6]['boat_3_percentages']);
    }
}
