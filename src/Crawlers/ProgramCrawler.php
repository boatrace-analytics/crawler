<?php

namespace Boatrace\Analytics\Crawlers;

use Goutte\Client as Goutte;
use Boatrace\Analytics\Converter;
use Carbon\CarbonImmutable as Carbon;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @author shimomo
 */
class ProgramCrawler extends BaseCrawler
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

        $crawlerFormat = '%s/owpc/pc/race/racelist?hd=%s&jcd=%02d&rno=%d';
        $crawlerUrl = sprintf($crawlerFormat, $this->baseUrl, $boatraceDate, $stadiumId, $raceNumber);
        $crawler = $this->goutte->request('GET', $crawlerUrl);
        sleep($seconds);

        $this->baseLevel = 0;

        $levelFormat = '%s/div[2]/div[4]/table/thead/tr[1]/th[1]';
        $levelXPath = sprintf($levelFormat, $this->baseXPath);
        if (is_null($this->filterXPath($crawler, $levelXPath))) {
            $this->baseLevel = 1;
        }

        $titleFormat = '%s/div[1]/div/div[2]/h2';
        $subtitleDistanceFormat = '%s/div[1]/div/div[2]/span';
        $deadlineFormat = '%s/div[2]/div[2]/table/tbody/tr[1]/td[%s]';

        $titleXPath = sprintf($titleFormat, $this->baseXPath);
        $subtitleDistanceXPath = sprintf($subtitleDistanceFormat, $this->baseXPath);
        $deadlineXPath = sprintf($deadlineFormat, $this->baseXPath, $raceNumber + 1);

        $title = $this->filterXPath($crawler, $titleXPath);
        $subtitleDistance = $this->filterXPath($crawler, $subtitleDistanceXPath);
        $deadline = $this->filterXPath($crawler, $deadlineXPath);

        $closedAt = is_null($deadline) ? null : Converter::convertToDateTime($date . ' ' . $deadline . ':00');

        [$subtitle, $distance] = $this->explodeSubtitleDistance($subtitleDistance);

        $response['stadiums'][$stadiumId]['races'][$raceNumber]['date'] = $date;
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['stadium_id'] = $stadiumId;
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['race_number'] = $raceNumber;
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['title'] = $title;
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['subtitle'] = $subtitle;
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['distance'] = $distance;
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['closed_at'] = $closedAt;

        $response = $this->crawlRacers($crawler, $response, $date, $stadiumId, $raceNumber);

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
    protected function crawlRacers(Crawler $crawler, array $response, string $date, int $stadiumId, int $raceNumber): array
    {
        $nameFormat = '%s/div[2]/div[%s]/table/tbody[%s]/tr[1]/td[3]/div[2]/a';
        $numberClassNameFormat = '%s/div[2]/div[%s]/table/tbody[%s]/tr[1]/td[3]/div[1]';
        $branchNameBirthplaceNameAgeWeightFormat = '%s/div[2]/div[%s]/table/tbody[%s]/tr[1]/td[3]/div[3]';
        $flyingCountLateCountStartTimingFormat = '%s/div[2]/div[%s]/table/tbody[%s]/tr[1]/td[4]';
        $national123PercentagesFormat = '%s/div[2]/div[%s]/table/tbody[%s]/tr[1]/td[5]';
        $local123PercentagesFormat = '%s/div[2]/div[%s]/table/tbody[%s]/tr[1]/td[6]';
        $motorNumberMotor23PercentagesFormat = '%s/div[2]/div[%s]/table/tbody[%s]/tr[1]/td[7]';
        $boatNumberBoat23PercentagesFormat = '%s/div[2]/div[%s]/table/tbody[%s]/tr[1]/td[8]';

        foreach (range(1, 6) as $bracket) {
            $nameXPath = sprintf($nameFormat, $this->baseXPath, $this->baseLevel + 4, $bracket);
            $numberClassNameXPath = sprintf($numberClassNameFormat, $this->baseXPath, $this->baseLevel + 4, $bracket);
            $branchNameBirthplaceNameAgeWeightXPath = sprintf($branchNameBirthplaceNameAgeWeightFormat, $this->baseXPath, $this->baseLevel + 4, $bracket);
            $flyingCountLateCountStartTimingXPath = sprintf($flyingCountLateCountStartTimingFormat, $this->baseXPath, $this->baseLevel + 4, $bracket);
            $national123PercentagesXPath = sprintf($national123PercentagesFormat, $this->baseXPath, $this->baseLevel + 4, $bracket);
            $local123PercentagesXPath = sprintf($local123PercentagesFormat, $this->baseXPath, $this->baseLevel + 4, $bracket);
            $motorNumberMotor23PercentagesXPath = sprintf($motorNumberMotor23PercentagesFormat, $this->baseXPath, $this->baseLevel + 4, $bracket);
            $boatNumberMotor23PercentagesXPath = sprintf($boatNumberBoat23PercentagesFormat, $this->baseXPath, $this->baseLevel + 4, $bracket);

            $name = $this->filterXPath($crawler, $nameXPath);
            $numberClassName = $this->filterXPath($crawler, $numberClassNameXPath);
            $branchNameBirthplaceNameAgeWeight = $this->filterXPath($crawler, $branchNameBirthplaceNameAgeWeightXPath);
            $flyingCountLateCountStartTiming = $this->filterXPath($crawler, $flyingCountLateCountStartTimingXPath);
            $national123Percentages = $this->filterXPath($crawler, $national123PercentagesXPath);
            $local123Percentages = $this->filterXPath($crawler, $local123PercentagesXPath);
            $motorNumberMotor23Percentages = $this->filterXPath($crawler, $motorNumberMotor23PercentagesXPath);
            $boatNumberBoat23Percentages = $this->filterXPath($crawler, $boatNumberMotor23PercentagesXPath);

            $name = is_null($name) ? null : Converter::convertToName($name);

            [$number, $classId] = $this->explodeNumberClassName($numberClassName);
            [$branchId, $birthplaceId, $age, $weight] = $this->explodeBranchNameBirthplaceNameAgeWeight($branchNameBirthplaceNameAgeWeight);
            [$flyingCount, $lateCount, $startTiming] = $this->explodeFlyingCountLateCountStartTiming($flyingCountLateCountStartTiming);
            [$national1Percentages, $national2Percentages, $national3Percentages] = $this->explodeNational123Percentages($national123Percentages);
            [$local1Percentages, $local2Percentages, $local3Percentages] = $this->explodeLocal123Percentages($local123Percentages);
            [$motorNumber, $motor2Percentages, $motor3Percentages] = $this->explodeMotorNumberMotor23Percentages($motorNumberMotor23Percentages);
            [$boatNumber, $boat2Percentages, $boat3Percentages] = $this->explodeBoatNumberBoat23Percentages($boatNumberBoat23Percentages);

            $response['stadiums'][$stadiumId]['races'][$raceNumber]['racers'][$bracket]['bracket'] = $bracket;
            $response['stadiums'][$stadiumId]['races'][$raceNumber]['racers'][$bracket]['name'] = $name;
            $response['stadiums'][$stadiumId]['races'][$raceNumber]['racers'][$bracket]['number'] = $number;
            $response['stadiums'][$stadiumId]['races'][$raceNumber]['racers'][$bracket]['class_id'] = $classId;
            $response['stadiums'][$stadiumId]['races'][$raceNumber]['racers'][$bracket]['branch_id'] = $branchId;
            $response['stadiums'][$stadiumId]['races'][$raceNumber]['racers'][$bracket]['birthplace_id'] = $birthplaceId;
            $response['stadiums'][$stadiumId]['races'][$raceNumber]['racers'][$bracket]['age'] = $age;
            $response['stadiums'][$stadiumId]['races'][$raceNumber]['racers'][$bracket]['weight'] = $weight;
            $response['stadiums'][$stadiumId]['races'][$raceNumber]['racers'][$bracket]['flying_count'] = $flyingCount;
            $response['stadiums'][$stadiumId]['races'][$raceNumber]['racers'][$bracket]['late_count'] = $lateCount;
            $response['stadiums'][$stadiumId]['races'][$raceNumber]['racers'][$bracket]['start_timing'] = $startTiming;
            $response['stadiums'][$stadiumId]['races'][$raceNumber]['racers'][$bracket]['national_1_percentages'] = $national1Percentages;
            $response['stadiums'][$stadiumId]['races'][$raceNumber]['racers'][$bracket]['national_2_percentages'] = $national2Percentages;
            $response['stadiums'][$stadiumId]['races'][$raceNumber]['racers'][$bracket]['national_3_percentages'] = $national3Percentages;
            $response['stadiums'][$stadiumId]['races'][$raceNumber]['racers'][$bracket]['local_1_percentages'] = $local1Percentages;
            $response['stadiums'][$stadiumId]['races'][$raceNumber]['racers'][$bracket]['local_2_percentages'] = $local2Percentages;
            $response['stadiums'][$stadiumId]['races'][$raceNumber]['racers'][$bracket]['local_3_percentages'] = $local3Percentages;
            $response['stadiums'][$stadiumId]['races'][$raceNumber]['racers'][$bracket]['motor_number'] = $motorNumber;
            $response['stadiums'][$stadiumId]['races'][$raceNumber]['racers'][$bracket]['motor_2_percentages'] = $motor2Percentages;
            $response['stadiums'][$stadiumId]['races'][$raceNumber]['racers'][$bracket]['motor_3_percentages'] = $motor3Percentages;
            $response['stadiums'][$stadiumId]['races'][$raceNumber]['racers'][$bracket]['boat_number'] = $boatNumber;
            $response['stadiums'][$stadiumId]['races'][$raceNumber]['racers'][$bracket]['boat_2_percentages'] = $boat2Percentages;
            $response['stadiums'][$stadiumId]['races'][$raceNumber]['racers'][$bracket]['boat_3_percentages'] = $boat3Percentages;
        }

        return $response;
    }

    /**
     * @param  string|null  $subtitleDistance
     * @return array
     */
    protected function explodeSubtitleDistance(string $subtitleDistance = null): array
    {
        if (is_null($subtitleDistance)) {
            return array_fill(0, 2, null);
        }

        $values = array_filter(explode(' ', trim($subtitleDistance)));
        $distance = Converter::convertToInt(array_pop($values));
        $subtitle = Converter::convertToString(implode($values));

        return [$subtitle, $distance];
    }

    /**
     * @param  string|null  $numberClassName
     * @return array
     */
    protected function explodeNumberClassName(string $numberClassName = null): array
    {
        if (is_null($numberClassName)) {
            return array_fill(0, 2, null);
        }

        [$number, $className] = explode('/', trim($numberClassName));

        $number = Converter::convertToInt($number);
        $classId = Converter::convertToClassId($className);

        return [$number, $classId];
    }

    /**
     * @param  string|null  $branchNameBirthplaceNameAgeWeight
     * @return array
     */
    protected function explodeBranchNameBirthplaceNameAgeWeight(string $branchNameBirthplaceNameAgeWeight = null): array
    {
        if (is_null($branchNameBirthplaceNameAgeWeight)) {
            return array_fill(0, 4, null);
        }

        [$branchNameBirthplaceName, $ageWeight] = explode(' ', trim($branchNameBirthplaceNameAgeWeight));
        [$branchName, $birthplaceName] = explode('/', trim($branchNameBirthplaceName));
        [$age, $weight] = explode('/', trim($ageWeight));

        $branchId = Converter::convertToPrefectureId($branchName);
        $birthplaceId = Converter::convertToPrefectureId($birthplaceName);
        $age = Converter::convertToInt($age);
        $weight = Converter::convertToFloat($weight);

        return [$branchId, $birthplaceId, $age, $weight];
    }

    /**
     * @param  string|null  $flyingCountLateCountStartTiming
     * @return array
     */
    protected function explodeFlyingCountLateCountStartTiming(string $flyingCountLateCountStartTiming = null): array
    {
        if (is_null($flyingCountLateCountStartTiming)) {
            return array_fill(0, 3, null);
        }

        [$flyingCount, $lateCount, $startTiming] = explode(' ', trim($flyingCountLateCountStartTiming));

        $flyingCount = Converter::convertToFlying($flyingCount);
        $lateCount = Converter::convertToLate($lateCount);
        $startTiming = Converter::convertToStartTiming($startTiming);

        return [$flyingCount, $lateCount, $startTiming];
    }

    /**
     * @param  string|null  $national123Percentages
     * @return array
     */
    protected function explodeNational123Percentages(string $national123Percentages = null): array
    {
        if (is_null($national123Percentages)) {
            return array_fill(0, 3, null);
        }

        [$national1Percentages, $national2Percentages, $national3Percentages] = explode(' ', trim($national123Percentages));

        $national1Percentages = Converter::convertToFloat($national1Percentages);
        $national2Percentages = Converter::convertToFloat($national2Percentages);
        $national3Percentages = Converter::convertToFloat($national3Percentages);

        return [$national1Percentages, $national2Percentages, $national3Percentages];
    }

    /**
     * @param  string|null  $local123Percentages
     * @return array
     */
    protected function explodeLocal123Percentages(string $local123Percentages = null): array
    {
        if (is_null($local123Percentages)) {
            return array_fill(0, 3, null);
        }

        [$local1Percentages, $local2Percentages, $local3Percentages] = explode(' ', trim($local123Percentages));

        $local1Percentages = Converter::convertToFloat($local1Percentages);
        $local2Percentages = Converter::convertToFloat($local2Percentages);
        $local3Percentages = Converter::convertToFloat($local3Percentages);

        return [$local1Percentages, $local2Percentages, $local3Percentages];
    }

    /**
     * @param  string|null  $motorNumberMotor23Percentages
     * @return array
     */
    protected function explodeMotorNumberMotor23Percentages(string $motorNumberMotor23Percentages = null): array
    {
        if (is_null($motorNumberMotor23Percentages)) {
            return array_fill(0, 3, null);
        }

        [$motorNumber, $motor2Percentages, $motor3Percentages] = explode(' ', trim($motorNumberMotor23Percentages));

        $motorNumber = Converter::convertToInt($motorNumber);
        $motor2Percentages = Converter::convertToFloat($motor2Percentages);
        $motor3Percentages = Converter::convertToFloat($motor3Percentages);

        return [$motorNumber, $motor2Percentages, $motor3Percentages];
    }

    /**
     * @param  string|null  $boatNumberBoat23Percentages
     * @return array
     */
    protected function explodeBoatNumberBoat23Percentages(string $boatNumberBoat23Percentages = null): array
    {
        if (is_null($boatNumberBoat23Percentages)) {
            return array_fill(0, 3, null);
        }

        [$boatNumber, $boat2Percentages, $boat3Percentages] = explode(' ', trim($boatNumberBoat23Percentages));

        $boatNumber = Converter::convertToInt($boatNumber);
        $boat2Percentages = Converter::convertToFloat($boat2Percentages);
        $boat3Percentages = Converter::convertToFloat($boat3Percentages);

        return [$boatNumber, $boat2Percentages, $boat3Percentages];
    }
}
