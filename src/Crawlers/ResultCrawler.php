<?php

namespace Boatrace\Analytics\Crawlers;

use Goutte\Client as Goutte;
use Boatrace\Analytics\Converter;
use Carbon\CarbonImmutable as Carbon;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @author shimomo
 */
class ResultCrawler extends BaseCrawler
{
    /**
     * @var int
     */
    protected $baseLevel = 0;

    /**
     * @var string
     */
    protected $baseXPath = 'descendant-or-self::body/main/div/div/div';

    /**
     * @param  \Goutte\Client  $goutte
     * @return void
     */
    public function __construct(Goutte $goutte)
    {
        parent::__construct($goutte);
    }

    /**
     * @param  array   $response
     * @param  string  $date
     * @param  int     $stadiumId
     * @param  int     $raceNumber
     * @param  int     $seconds
     * @return array
     */
    public function crawl(array $response, string $date, int $stadiumId, int $raceNumber, int $seconds): array
    {
        $date = Converter::convertToDate($date);
        $stadiumId = Converter::convertToInt($stadiumId);
        $raceNumber = Converter::convertToInt($raceNumber);

        $boatraceDate = Carbon::parse($date)->format('Ymd');

        $crawlerFormat = '%s/owpc/pc/race/raceresult?hd=%s&jcd=%02d&rno=%d';
        $crawlerUrl = sprintf($crawlerFormat, $this->baseUrl, $boatraceDate, $stadiumId, $raceNumber);
        $crawler = $this->goutte->request('GET', $crawlerUrl);
        sleep($seconds);

        $this->baseLevel = 0;

        $levelFormat = '%s/div[2]/div[4]/div[1]/div/table/thead/tr/th[1]';
        $levelXPath = sprintf($levelFormat, $this->baseXPath);
        if (is_null($this->filterXPath($crawler, $levelXPath))) {
            $this->baseLevel = 1;
        }

        $windFormat = '%s/div[2]/div[%s]/div[2]/div[1]/div[1]/div/div[1]/div[3]/div/span[2]';
        $waveFormat = '%s/div[2]/div[%s]/div[2]/div[1]/div[1]/div/div[1]/div[6]/div/span[2]';
        $weatherNameFormat = '%s/div[2]/div[%s]/div[2]/div[1]/div[1]/div/div[1]/div[2]/div/span';
        $temperatureFormat = '%s/div[2]/div[%s]/div[2]/div[1]/div[1]/div/div[1]/div[1]/div/span[2]';
        $waterTemperatureFormat = '%s/div[2]/div[%s]/div[2]/div[1]/div[1]/div/div[1]/div[5]/div/span[2]';
        $techniqueNameFormat = '%s/div[2]/div[%s]/div[2]/div[1]/div[2]/div[2]/table/tbody/tr/td';

        $windXPath = sprintf($windFormat, $this->baseXPath, $this->baseLevel + 5);
        $waveXPath = sprintf($waveFormat, $this->baseXPath, $this->baseLevel + 5);
        $weatherNameXPath = sprintf($weatherNameFormat, $this->baseXPath, $this->baseLevel + 5);
        $temperatureXPath = sprintf($temperatureFormat, $this->baseXPath, $this->baseLevel + 5);
        $waterTemperatureXPath = sprintf($waterTemperatureFormat, $this->baseXPath, $this->baseLevel + 5);
        $techniqueNameXPath = sprintf($techniqueNameFormat, $this->baseXPath, $this->baseLevel + 5);

        $wind = $this->filterXPath($crawler, $windXPath);
        $wave = $this->filterXPath($crawler, $waveXPath);
        $weatherName = $this->filterXPath($crawler, $weatherNameXPath);
        $temperature = $this->filterXPath($crawler, $temperatureXPath);
        $waterTemperature = $this->filterXPath($crawler, $waterTemperatureXPath);
        $techniqueName = $this->filterXPath($crawler, $techniqueNameXPath);

        $wind = is_null($wind) ? null : Converter::convertToWind($wind);
        $wave = is_null($wave) ? null : Converter::convertToWave($wave);
        $weatherId = is_null($weatherName) ? null : Converter::convertToWeatherId($weatherName);
        $temperature = is_null($temperature) ? null : Converter::convertToTemperature($temperature);
        $waterTemperature = is_null($waterTemperature) ? null : Converter::convertToTemperature($waterTemperature);
        $techniqueId = is_null($techniqueName) ? null : Converter::convertToTechniqueId($techniqueName);

        $response['stadiums'][$stadiumId]['races'][$raceNumber]['date'] = $date;
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['stadium_id'] = $stadiumId;
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['race_number'] = $raceNumber;
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['wind'] = $wind;
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['wave'] = $wave;
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['weather_id'] = $weatherId;
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['temperature'] = $temperature;
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['water_temperature'] = $waterTemperature;
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['technique_id'] = $techniqueId;

        $response = $this->crawlPlaces($crawler, $response, $date, $stadiumId, $raceNumber);
        $response = $this->crawlCourses($crawler, $response, $date, $stadiumId, $raceNumber);

        return $response;
    }

    /**
     * @param  \Symfony\Component\DomCrawler\Crawler  $crawler
     * @param  array                                  $response
     * @param  string                                 $date
     * @param  int                                    $stadiumId
     * @param  int                                    $raceNumber
     * @return array
     */
    protected function crawlPlaces(Crawler $crawler, array $response, string $date, int $stadiumId, int $raceNumber): array
    {
        $placeFormat = '%s/div[2]/div[%s]/div[1]/div/table/tbody[%s]/tr/td[1]';
        $bracketFormat = '%s/div[2]/div[%s]/div[1]/div/table/tbody[%s]/tr/td[2]';
        $racerNumberFormat = '%s/div[2]/div[%s]/div[1]/div/table/tbody[%s]/tr/td[3]/span[1]';
        $racerNameFormat = '%s/div[2]/div[%s]/div[1]/div/table/tbody[%s]/tr/td[3]/span[2]';

        foreach (range(1, 6) as $index) {
            $placeXPath = sprintf($placeFormat, $this->baseXPath, $this->baseLevel + 4, $index);
            $bracketXPath = sprintf($bracketFormat, $this->baseXPath, $this->baseLevel + 4, $index);
            $racerNumberXPath = sprintf($racerNumberFormat, $this->baseXPath, $this->baseLevel + 4, $index);
            $racerNameXPath = sprintf($racerNameFormat, $this->baseXPath, $this->baseLevel + 4, $index);

            $place = $this->filterXPath($crawler, $placeXPath);
            $bracket = $this->filterXPath($crawler, $bracketXPath);
            $racerNumber = $this->filterXPath($crawler, $racerNumberXPath);
            $racerName = $this->filterXPath($crawler, $racerNameXPath);

            $place = is_null($place) ? null : Converter::convertToPlaceId($place);
            $bracket = is_null($bracket) ? null : Converter::convertToInt($bracket);
            $racerNumber = is_null($racerNumber) ? null : Converter::convertToInt($racerNumber);
            $racerName = is_null($racerName) ? null : Converter::convertToName($racerName);

            $response['stadiums'][$stadiumId]['races'][$raceNumber]['places'][$index]['place'] = $place;
            $response['stadiums'][$stadiumId]['races'][$raceNumber]['places'][$index]['bracket'] = $bracket;
            $response['stadiums'][$stadiumId]['races'][$raceNumber]['places'][$index]['racer_number'] = $racerNumber;
            $response['stadiums'][$stadiumId]['races'][$raceNumber]['places'][$index]['racer_name'] = $racerName;
        }

        return $response;
    }

    /**
     * @param  \Symfony\Component\DomCrawler\Crawler  $crawler
     * @param  array                                  $response
     * @param  string                                 $date
     * @param  int                                    $stadiumId
     * @param  int                                    $raceNumber
     * @return array
     */
    protected function crawlCourses(Crawler $crawler, array $response, string $date, int $stadiumId, int $raceNumber): array
    {
        $bracketFormat = '%s/div[2]/div[%s]/div[2]/div/table/tbody/tr[%s]/td/div/span[1]';
        $startTimingFormat = '%s/div[2]/div[%s]/div[2]/div/table/tbody/tr[%s]/td/div/span[3]/span';

        foreach (range(1, 6) as $course) {
            $bracketXPath = sprintf($bracketFormat, $this->baseXPath, $this->baseLevel + 4, $course);
            $startTimingXPath = sprintf($startTimingFormat, $this->baseXPath, $this->baseLevel + 4, $course);

            $bracket = $this->filterXPath($crawler, $bracketXPath);
            $startTiming = $this->filterXPath($crawler, $startTimingXPath);

            $bracket = is_null($bracket) ? null : Converter::convertToInt($bracket);
            $startTiming = is_null($startTiming) ? null : Converter::convertToStartTiming($startTiming);

            $response['stadiums'][$stadiumId]['races'][$raceNumber]['courses'][$course]['course'] = $course;
            $response['stadiums'][$stadiumId]['races'][$raceNumber]['courses'][$course]['bracket'] = $bracket;
            $response['stadiums'][$stadiumId]['races'][$raceNumber]['courses'][$course]['start_timing'] = $startTiming;
        }

        return $response;
    }
}
