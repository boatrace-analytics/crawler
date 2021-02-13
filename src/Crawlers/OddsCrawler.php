<?php

namespace Boatrace\Analytics\Crawlers;

use Goutte\Client as Goutte;
use Boatrace\Analytics\Converter;
use Carbon\CarbonImmutable as Carbon;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @author shimomo
 */
class OddsCrawler extends BaseCrawler
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

        $request1 = sprintf('%s/owpc/pc/race/oddstf?hd=%s&jcd=%02d&rno=%d', $this->baseUrl, $boatraceDate, $stadiumId, $raceNumber);
        $crawler1 = $this->goutte->request('GET', $request1);
        sleep($seconds);

        $request2 = sprintf('%s/owpc/pc/race/odds2tf?hd=%s&jcd=%02d&rno=%d', $this->baseUrl, $boatraceDate, $stadiumId, $raceNumber);
        $crawler2 = $this->goutte->request('GET', $request2);
        sleep($seconds);

        $request3 = sprintf('%s/owpc/pc/race/oddsk?hd=%s&jcd=%02d&rno=%d', $this->baseUrl, $boatraceDate, $stadiumId, $raceNumber);
        $crawler3 = $this->goutte->request('GET', $request3);
        sleep($seconds);

        $request4 = sprintf('%s/owpc/pc/race/odds3t?hd=%s&jcd=%02d&rno=%d', $this->baseUrl, $boatraceDate, $stadiumId, $raceNumber);
        $crawler4 = $this->goutte->request('GET', $request4);
        sleep($seconds);

        $request5 = sprintf('%s/owpc/pc/race/odds3f?hd=%s&jcd=%02d&rno=%d', $this->baseUrl, $boatraceDate, $stadiumId, $raceNumber);
        $crawler5 = $this->goutte->request('GET', $request5);
        sleep($seconds);

        $this->baseLevel = 0;

        $levelFormat = '%s/div[2]/div[5]/div[1]/div[2]/table/tbody[1]/tr/td[1]';
        $levelXPath = sprintf($levelFormat, $this->baseXPath);
        if (is_null($this->filterXPath($crawler1, $levelXPath))) {
            $this->baseLevel = 1;
        }

        $response['stadiums'][$stadiumId]['races'][$raceNumber]['date'] = $date;
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['stadium_id'] = $stadiumId;
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['race_number'] = $raceNumber;

        $response = $this->crawlWin($crawler1, $response, $date, $stadiumId, $raceNumber);
        $response = $this->crawlPlace($crawler1, $response, $date, $stadiumId, $raceNumber);
        $response = $this->crawlExacta($crawler2, $response, $date, $stadiumId, $raceNumber);
        $response = $this->crawlQuinella($crawler2, $response, $date, $stadiumId, $raceNumber);
        $response = $this->crawlQuinellaPlace($crawler3, $response, $date, $stadiumId, $raceNumber);
        $response = $this->crawlTrifecta($crawler4, $response, $date, $stadiumId, $raceNumber);
        $response = $this->crawlTrio($crawler5, $response, $date, $stadiumId, $raceNumber);

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
    protected function crawlTrifecta(Crawler $crawler, array $response, string $date, int $stadiumId, int $raceNumber): array
    {
        $trifecta123XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[1]/td[3]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta124XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[2]/td[2]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta125XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[3]/td[2]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta126XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[4]/td[2]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta132XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[5]/td[3]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta134XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[6]/td[2]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta135XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[7]/td[2]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta136XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[8]/td[2]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta142XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[9]/td[3]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta143XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[10]/td[2]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta145XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[11]/td[2]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta146XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[12]/td[2]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta152XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[13]/td[3]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta153XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[14]/td[2]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta154XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[15]/td[2]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta156XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[16]/td[2]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta162XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[17]/td[3]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta163XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[18]/td[2]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta164XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[19]/td[2]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta165XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[20]/td[2]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta213XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[1]/td[6]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta214XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[2]/td[4]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta215XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[3]/td[4]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta216XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[4]/td[4]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta231XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[5]/td[6]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta234XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[6]/td[4]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta235XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[7]/td[4]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta236XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[8]/td[4]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta241XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[9]/td[6]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta243XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[10]/td[4]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta245XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[11]/td[4]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta246XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[12]/td[4]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta251XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[13]/td[6]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta253XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[14]/td[4]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta254XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[15]/td[4]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta256XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[16]/td[4]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta261XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[17]/td[6]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta263XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[18]/td[4]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta264XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[19]/td[4]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta265XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[20]/td[4]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta312XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[1]/td[9]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta314XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[2]/td[6]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta315XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[3]/td[6]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta316XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[4]/td[6]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta321XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[5]/td[9]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta324XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[6]/td[6]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta325XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[7]/td[6]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta326XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[8]/td[6]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta341XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[9]/td[9]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta342XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[10]/td[6]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta345XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[11]/td[6]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta346XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[12]/td[6]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta351XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[13]/td[9]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta352XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[14]/td[6]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta354XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[15]/td[6]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta356XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[16]/td[6]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta361XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[17]/td[9]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta362XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[18]/td[6]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta364XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[19]/td[6]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta365XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[20]/td[6]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta412XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[1]/td[12]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta413XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[2]/td[8]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta415XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[3]/td[8]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta416XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[4]/td[8]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta421XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[5]/td[12]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta423XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[6]/td[8]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta425XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[7]/td[8]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta426XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[8]/td[8]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta431XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[9]/td[12]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta432XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[10]/td[8]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta435XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[11]/td[8]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta436XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[12]/td[8]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta451XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[13]/td[12]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta452XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[14]/td[8]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta453XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[15]/td[8]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta456XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[16]/td[8]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta461XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[17]/td[12]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta462XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[18]/td[8]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta463XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[19]/td[8]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta465XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[20]/td[8]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta512XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[1]/td[15]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta513XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[2]/td[10]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta514XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[3]/td[10]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta516XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[4]/td[10]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta521XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[5]/td[15]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta523XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[6]/td[10]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta524XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[7]/td[10]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta526XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[8]/td[10]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta531XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[9]/td[15]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta532XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[10]/td[10]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta534XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[11]/td[10]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta536XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[12]/td[10]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta541XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[13]/td[15]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta542XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[14]/td[10]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta543XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[15]/td[10]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta546XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[16]/td[10]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta561XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[17]/td[15]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta562XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[18]/td[10]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta563XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[19]/td[10]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta564XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[20]/td[10]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta612XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[1]/td[18]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta613XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[2]/td[12]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta614XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[3]/td[12]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta615XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[4]/td[12]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta621XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[5]/td[18]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta623XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[6]/td[12]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta624XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[7]/td[12]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta625XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[8]/td[12]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta631XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[9]/td[18]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta632XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[10]/td[12]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta634XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[11]/td[12]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta635XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[12]/td[12]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta641XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[13]/td[18]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta642XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[14]/td[12]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta643XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[15]/td[12]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta645XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[16]/td[12]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta651XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[17]/td[18]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta652XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[18]/td[12]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta653XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[19]/td[12]', $this->baseXPath, $this->baseLevel + 6);
        $trifecta654XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[20]/td[12]', $this->baseXPath, $this->baseLevel + 6);

        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][1][2][3] = $this->filterXPathForOdds($crawler, $trifecta123XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][1][2][4] = $this->filterXPathForOdds($crawler, $trifecta124XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][1][2][5] = $this->filterXPathForOdds($crawler, $trifecta125XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][1][2][6] = $this->filterXPathForOdds($crawler, $trifecta126XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][1][3][2] = $this->filterXPathForOdds($crawler, $trifecta132XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][1][3][4] = $this->filterXPathForOdds($crawler, $trifecta134XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][1][3][5] = $this->filterXPathForOdds($crawler, $trifecta135XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][1][3][6] = $this->filterXPathForOdds($crawler, $trifecta136XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][1][4][2] = $this->filterXPathForOdds($crawler, $trifecta142XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][1][4][3] = $this->filterXPathForOdds($crawler, $trifecta143XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][1][4][5] = $this->filterXPathForOdds($crawler, $trifecta145XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][1][4][6] = $this->filterXPathForOdds($crawler, $trifecta146XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][1][5][2] = $this->filterXPathForOdds($crawler, $trifecta152XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][1][5][3] = $this->filterXPathForOdds($crawler, $trifecta153XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][1][5][4] = $this->filterXPathForOdds($crawler, $trifecta154XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][1][5][6] = $this->filterXPathForOdds($crawler, $trifecta156XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][1][6][2] = $this->filterXPathForOdds($crawler, $trifecta162XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][1][6][3] = $this->filterXPathForOdds($crawler, $trifecta163XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][1][6][4] = $this->filterXPathForOdds($crawler, $trifecta164XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][1][6][5] = $this->filterXPathForOdds($crawler, $trifecta165XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][2][1][3] = $this->filterXPathForOdds($crawler, $trifecta213XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][2][1][4] = $this->filterXPathForOdds($crawler, $trifecta214XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][2][1][5] = $this->filterXPathForOdds($crawler, $trifecta215XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][2][1][6] = $this->filterXPathForOdds($crawler, $trifecta216XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][2][3][1] = $this->filterXPathForOdds($crawler, $trifecta231XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][2][3][4] = $this->filterXPathForOdds($crawler, $trifecta234XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][2][3][5] = $this->filterXPathForOdds($crawler, $trifecta235XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][2][3][6] = $this->filterXPathForOdds($crawler, $trifecta236XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][2][4][1] = $this->filterXPathForOdds($crawler, $trifecta241XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][2][4][3] = $this->filterXPathForOdds($crawler, $trifecta243XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][2][4][5] = $this->filterXPathForOdds($crawler, $trifecta245XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][2][4][6] = $this->filterXPathForOdds($crawler, $trifecta246XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][2][5][1] = $this->filterXPathForOdds($crawler, $trifecta251XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][2][5][3] = $this->filterXPathForOdds($crawler, $trifecta253XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][2][5][4] = $this->filterXPathForOdds($crawler, $trifecta254XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][2][5][6] = $this->filterXPathForOdds($crawler, $trifecta256XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][2][6][1] = $this->filterXPathForOdds($crawler, $trifecta261XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][2][6][3] = $this->filterXPathForOdds($crawler, $trifecta263XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][2][6][4] = $this->filterXPathForOdds($crawler, $trifecta264XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][2][6][5] = $this->filterXPathForOdds($crawler, $trifecta265XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][3][1][2] = $this->filterXPathForOdds($crawler, $trifecta312XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][3][1][4] = $this->filterXPathForOdds($crawler, $trifecta314XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][3][1][5] = $this->filterXPathForOdds($crawler, $trifecta315XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][3][1][6] = $this->filterXPathForOdds($crawler, $trifecta316XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][3][2][1] = $this->filterXPathForOdds($crawler, $trifecta321XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][3][2][4] = $this->filterXPathForOdds($crawler, $trifecta324XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][3][2][5] = $this->filterXPathForOdds($crawler, $trifecta325XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][3][2][6] = $this->filterXPathForOdds($crawler, $trifecta326XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][3][4][1] = $this->filterXPathForOdds($crawler, $trifecta341XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][3][4][2] = $this->filterXPathForOdds($crawler, $trifecta342XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][3][4][5] = $this->filterXPathForOdds($crawler, $trifecta345XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][3][4][6] = $this->filterXPathForOdds($crawler, $trifecta346XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][3][5][1] = $this->filterXPathForOdds($crawler, $trifecta351XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][3][5][2] = $this->filterXPathForOdds($crawler, $trifecta352XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][3][5][4] = $this->filterXPathForOdds($crawler, $trifecta354XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][3][5][6] = $this->filterXPathForOdds($crawler, $trifecta356XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][3][6][1] = $this->filterXPathForOdds($crawler, $trifecta361XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][3][6][2] = $this->filterXPathForOdds($crawler, $trifecta362XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][3][6][4] = $this->filterXPathForOdds($crawler, $trifecta364XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][3][6][5] = $this->filterXPathForOdds($crawler, $trifecta365XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][4][1][2] = $this->filterXPathForOdds($crawler, $trifecta412XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][4][1][3] = $this->filterXPathForOdds($crawler, $trifecta413XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][4][1][5] = $this->filterXPathForOdds($crawler, $trifecta415XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][4][1][6] = $this->filterXPathForOdds($crawler, $trifecta416XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][4][2][1] = $this->filterXPathForOdds($crawler, $trifecta421XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][4][2][3] = $this->filterXPathForOdds($crawler, $trifecta423XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][4][2][5] = $this->filterXPathForOdds($crawler, $trifecta425XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][4][2][6] = $this->filterXPathForOdds($crawler, $trifecta426XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][4][3][1] = $this->filterXPathForOdds($crawler, $trifecta431XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][4][3][2] = $this->filterXPathForOdds($crawler, $trifecta432XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][4][3][5] = $this->filterXPathForOdds($crawler, $trifecta435XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][4][3][6] = $this->filterXPathForOdds($crawler, $trifecta436XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][4][5][1] = $this->filterXPathForOdds($crawler, $trifecta451XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][4][5][2] = $this->filterXPathForOdds($crawler, $trifecta452XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][4][5][3] = $this->filterXPathForOdds($crawler, $trifecta453XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][4][5][6] = $this->filterXPathForOdds($crawler, $trifecta456XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][4][6][1] = $this->filterXPathForOdds($crawler, $trifecta461XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][4][6][2] = $this->filterXPathForOdds($crawler, $trifecta462XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][4][6][3] = $this->filterXPathForOdds($crawler, $trifecta463XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][4][6][5] = $this->filterXPathForOdds($crawler, $trifecta465XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][5][1][2] = $this->filterXPathForOdds($crawler, $trifecta512XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][5][1][3] = $this->filterXPathForOdds($crawler, $trifecta513XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][5][1][4] = $this->filterXPathForOdds($crawler, $trifecta514XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][5][1][6] = $this->filterXPathForOdds($crawler, $trifecta516XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][5][2][1] = $this->filterXPathForOdds($crawler, $trifecta521XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][5][2][3] = $this->filterXPathForOdds($crawler, $trifecta523XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][5][2][4] = $this->filterXPathForOdds($crawler, $trifecta524XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][5][2][6] = $this->filterXPathForOdds($crawler, $trifecta526XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][5][3][1] = $this->filterXPathForOdds($crawler, $trifecta531XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][5][3][2] = $this->filterXPathForOdds($crawler, $trifecta532XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][5][3][4] = $this->filterXPathForOdds($crawler, $trifecta534XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][5][3][6] = $this->filterXPathForOdds($crawler, $trifecta536XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][5][4][1] = $this->filterXPathForOdds($crawler, $trifecta541XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][5][4][2] = $this->filterXPathForOdds($crawler, $trifecta542XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][5][4][3] = $this->filterXPathForOdds($crawler, $trifecta543XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][5][4][6] = $this->filterXPathForOdds($crawler, $trifecta546XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][5][6][1] = $this->filterXPathForOdds($crawler, $trifecta561XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][5][6][2] = $this->filterXPathForOdds($crawler, $trifecta562XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][5][6][3] = $this->filterXPathForOdds($crawler, $trifecta563XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][5][6][4] = $this->filterXPathForOdds($crawler, $trifecta564XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][6][1][2] = $this->filterXPathForOdds($crawler, $trifecta612XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][6][1][3] = $this->filterXPathForOdds($crawler, $trifecta613XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][6][1][4] = $this->filterXPathForOdds($crawler, $trifecta614XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][6][1][5] = $this->filterXPathForOdds($crawler, $trifecta615XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][6][2][1] = $this->filterXPathForOdds($crawler, $trifecta621XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][6][2][3] = $this->filterXPathForOdds($crawler, $trifecta623XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][6][2][4] = $this->filterXPathForOdds($crawler, $trifecta624XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][6][2][5] = $this->filterXPathForOdds($crawler, $trifecta625XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][6][3][1] = $this->filterXPathForOdds($crawler, $trifecta631XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][6][3][2] = $this->filterXPathForOdds($crawler, $trifecta632XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][6][3][4] = $this->filterXPathForOdds($crawler, $trifecta634XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][6][3][5] = $this->filterXPathForOdds($crawler, $trifecta635XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][6][4][1] = $this->filterXPathForOdds($crawler, $trifecta641XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][6][4][2] = $this->filterXPathForOdds($crawler, $trifecta642XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][6][4][3] = $this->filterXPathForOdds($crawler, $trifecta643XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][6][4][5] = $this->filterXPathForOdds($crawler, $trifecta645XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][6][5][1] = $this->filterXPathForOdds($crawler, $trifecta651XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][6][5][2] = $this->filterXPathForOdds($crawler, $trifecta652XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][6][5][3] = $this->filterXPathForOdds($crawler, $trifecta653XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trifecta'][6][5][4] = $this->filterXPathForOdds($crawler, $trifecta654XPath);

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
    protected function crawlTrio(Crawler $crawler, array $response, string $date, int $stadiumId, int $raceNumber): array
    {
        $trio123XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[1]/td[3]', $this->baseXPath, $this->baseLevel + 6);
        $trio124XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[2]/td[2]', $this->baseXPath, $this->baseLevel + 6);
        $trio125XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[3]/td[2]', $this->baseXPath, $this->baseLevel + 6);
        $trio126XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[4]/td[2]', $this->baseXPath, $this->baseLevel + 6);
        $trio134XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[5]/td[3]', $this->baseXPath, $this->baseLevel + 6);
        $trio135XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[6]/td[2]', $this->baseXPath, $this->baseLevel + 6);
        $trio136XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[7]/td[2]', $this->baseXPath, $this->baseLevel + 6);
        $trio145XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[8]/td[3]', $this->baseXPath, $this->baseLevel + 6);
        $trio146XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[9]/td[2]', $this->baseXPath, $this->baseLevel + 6);
        $trio156XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[10]/td[3]', $this->baseXPath, $this->baseLevel + 6);
        $trio234XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[5]/td[6]', $this->baseXPath, $this->baseLevel + 6);
        $trio235XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[6]/td[4]', $this->baseXPath, $this->baseLevel + 6);
        $trio236XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[7]/td[4]', $this->baseXPath, $this->baseLevel + 6);
        $trio245XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[8]/td[6]', $this->baseXPath, $this->baseLevel + 6);
        $trio246XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[9]/td[4]', $this->baseXPath, $this->baseLevel + 6);
        $trio256XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[10]/td[6]', $this->baseXPath, $this->baseLevel + 6);
        $trio345XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[8]/td[9]', $this->baseXPath, $this->baseLevel + 6);
        $trio346XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[9]/td[6]', $this->baseXPath, $this->baseLevel + 6);
        $trio356XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[10]/td[9]', $this->baseXPath, $this->baseLevel + 6);
        $trio456XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[10]/td[12]', $this->baseXPath, $this->baseLevel + 6);

        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trio'][1][2][3] = $this->filterXPathForOdds($crawler, $trio123XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trio'][1][2][4] = $this->filterXPathForOdds($crawler, $trio124XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trio'][1][2][5] = $this->filterXPathForOdds($crawler, $trio125XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trio'][1][2][6] = $this->filterXPathForOdds($crawler, $trio126XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trio'][1][3][4] = $this->filterXPathForOdds($crawler, $trio134XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trio'][1][3][5] = $this->filterXPathForOdds($crawler, $trio135XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trio'][1][3][6] = $this->filterXPathForOdds($crawler, $trio136XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trio'][1][4][5] = $this->filterXPathForOdds($crawler, $trio145XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trio'][1][4][6] = $this->filterXPathForOdds($crawler, $trio146XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trio'][1][5][6] = $this->filterXPathForOdds($crawler, $trio156XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trio'][2][3][4] = $this->filterXPathForOdds($crawler, $trio234XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trio'][2][3][5] = $this->filterXPathForOdds($crawler, $trio235XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trio'][2][3][6] = $this->filterXPathForOdds($crawler, $trio236XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trio'][2][4][5] = $this->filterXPathForOdds($crawler, $trio245XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trio'][2][4][6] = $this->filterXPathForOdds($crawler, $trio246XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trio'][2][5][6] = $this->filterXPathForOdds($crawler, $trio256XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trio'][3][4][5] = $this->filterXPathForOdds($crawler, $trio345XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trio'][3][4][6] = $this->filterXPathForOdds($crawler, $trio346XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trio'][3][5][6] = $this->filterXPathForOdds($crawler, $trio356XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['trio'][4][5][6] = $this->filterXPathForOdds($crawler, $trio456XPath);

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
    protected function crawlExacta(Crawler $crawler, array $response, string $date, int $stadiumId, int $raceNumber): array
    {
        $exacta12XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[1]/td[2]', $this->baseXPath, $this->baseLevel + 6);
        $exacta13XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[2]/td[2]', $this->baseXPath, $this->baseLevel + 6);
        $exacta14XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[3]/td[2]', $this->baseXPath, $this->baseLevel + 6);
        $exacta15XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[4]/td[2]', $this->baseXPath, $this->baseLevel + 6);
        $exacta16XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[5]/td[2]', $this->baseXPath, $this->baseLevel + 6);
        $exacta21XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[1]/td[4]', $this->baseXPath, $this->baseLevel + 6);
        $exacta23XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[2]/td[4]', $this->baseXPath, $this->baseLevel + 6);
        $exacta24XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[3]/td[4]', $this->baseXPath, $this->baseLevel + 6);
        $exacta25XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[4]/td[4]', $this->baseXPath, $this->baseLevel + 6);
        $exacta26XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[5]/td[4]', $this->baseXPath, $this->baseLevel + 6);
        $exacta31XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[1]/td[6]', $this->baseXPath, $this->baseLevel + 6);
        $exacta32XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[2]/td[6]', $this->baseXPath, $this->baseLevel + 6);
        $exacta34XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[3]/td[6]', $this->baseXPath, $this->baseLevel + 6);
        $exacta35XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[4]/td[6]', $this->baseXPath, $this->baseLevel + 6);
        $exacta36XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[5]/td[6]', $this->baseXPath, $this->baseLevel + 6);
        $exacta41XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[1]/td[8]', $this->baseXPath, $this->baseLevel + 6);
        $exacta42XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[2]/td[8]', $this->baseXPath, $this->baseLevel + 6);
        $exacta43XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[3]/td[8]', $this->baseXPath, $this->baseLevel + 6);
        $exacta45XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[4]/td[8]', $this->baseXPath, $this->baseLevel + 6);
        $exacta46XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[5]/td[8]', $this->baseXPath, $this->baseLevel + 6);
        $exacta51XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[1]/td[10]', $this->baseXPath, $this->baseLevel + 6);
        $exacta52XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[2]/td[10]', $this->baseXPath, $this->baseLevel + 6);
        $exacta53XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[3]/td[10]', $this->baseXPath, $this->baseLevel + 6);
        $exacta54XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[4]/td[10]', $this->baseXPath, $this->baseLevel + 6);
        $exacta56XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[5]/td[10]', $this->baseXPath, $this->baseLevel + 6);
        $exacta61XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[1]/td[12]', $this->baseXPath, $this->baseLevel + 6);
        $exacta62XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[2]/td[12]', $this->baseXPath, $this->baseLevel + 6);
        $exacta63XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[3]/td[12]', $this->baseXPath, $this->baseLevel + 6);
        $exacta64XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[4]/td[12]', $this->baseXPath, $this->baseLevel + 6);
        $exacta65XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[5]/td[12]', $this->baseXPath, $this->baseLevel + 6);

        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['exacta'][1][2] = $this->filterXPathForOdds($crawler, $exacta12XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['exacta'][1][3] = $this->filterXPathForOdds($crawler, $exacta13XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['exacta'][1][4] = $this->filterXPathForOdds($crawler, $exacta14XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['exacta'][1][5] = $this->filterXPathForOdds($crawler, $exacta15XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['exacta'][1][6] = $this->filterXPathForOdds($crawler, $exacta16XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['exacta'][2][1] = $this->filterXPathForOdds($crawler, $exacta21XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['exacta'][2][3] = $this->filterXPathForOdds($crawler, $exacta23XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['exacta'][2][4] = $this->filterXPathForOdds($crawler, $exacta24XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['exacta'][2][5] = $this->filterXPathForOdds($crawler, $exacta25XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['exacta'][2][6] = $this->filterXPathForOdds($crawler, $exacta26XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['exacta'][3][1] = $this->filterXPathForOdds($crawler, $exacta31XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['exacta'][3][2] = $this->filterXPathForOdds($crawler, $exacta32XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['exacta'][3][4] = $this->filterXPathForOdds($crawler, $exacta34XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['exacta'][3][5] = $this->filterXPathForOdds($crawler, $exacta35XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['exacta'][3][6] = $this->filterXPathForOdds($crawler, $exacta36XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['exacta'][4][1] = $this->filterXPathForOdds($crawler, $exacta41XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['exacta'][4][2] = $this->filterXPathForOdds($crawler, $exacta42XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['exacta'][4][3] = $this->filterXPathForOdds($crawler, $exacta43XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['exacta'][4][5] = $this->filterXPathForOdds($crawler, $exacta45XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['exacta'][4][6] = $this->filterXPathForOdds($crawler, $exacta46XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['exacta'][5][1] = $this->filterXPathForOdds($crawler, $exacta51XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['exacta'][5][2] = $this->filterXPathForOdds($crawler, $exacta52XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['exacta'][5][3] = $this->filterXPathForOdds($crawler, $exacta53XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['exacta'][5][4] = $this->filterXPathForOdds($crawler, $exacta54XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['exacta'][5][6] = $this->filterXPathForOdds($crawler, $exacta56XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['exacta'][6][1] = $this->filterXPathForOdds($crawler, $exacta61XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['exacta'][6][2] = $this->filterXPathForOdds($crawler, $exacta62XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['exacta'][6][3] = $this->filterXPathForOdds($crawler, $exacta63XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['exacta'][6][4] = $this->filterXPathForOdds($crawler, $exacta64XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['exacta'][6][5] = $this->filterXPathForOdds($crawler, $exacta65XPath);

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
    protected function crawlQuinella(Crawler $crawler, array $response, string $date, int $stadiumId, int $raceNumber): array
    {
        $quinella12XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[1]/td[2]', $this->baseXPath, $this->baseLevel + 8);
        $quinella13XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[2]/td[2]', $this->baseXPath, $this->baseLevel + 8);
        $quinella14XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[3]/td[2]', $this->baseXPath, $this->baseLevel + 8);
        $quinella15XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[4]/td[2]', $this->baseXPath, $this->baseLevel + 8);
        $quinella16XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[5]/td[2]', $this->baseXPath, $this->baseLevel + 8);
        $quinella23XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[2]/td[4]', $this->baseXPath, $this->baseLevel + 8);
        $quinella24XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[3]/td[4]', $this->baseXPath, $this->baseLevel + 8);
        $quinella25XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[4]/td[4]', $this->baseXPath, $this->baseLevel + 8);
        $quinella26XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[5]/td[4]', $this->baseXPath, $this->baseLevel + 8);
        $quinella34XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[3]/td[6]', $this->baseXPath, $this->baseLevel + 8);
        $quinella35XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[4]/td[6]', $this->baseXPath, $this->baseLevel + 8);
        $quinella36XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[5]/td[6]', $this->baseXPath, $this->baseLevel + 8);
        $quinella45XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[4]/td[8]', $this->baseXPath, $this->baseLevel + 8);
        $quinella46XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[5]/td[8]', $this->baseXPath, $this->baseLevel + 8);
        $quinella56XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[5]/td[10]', $this->baseXPath, $this->baseLevel + 8);

        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['quinella'][1][2] = $this->filterXPathForOdds($crawler, $quinella12XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['quinella'][1][3] = $this->filterXPathForOdds($crawler, $quinella13XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['quinella'][1][4] = $this->filterXPathForOdds($crawler, $quinella14XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['quinella'][1][5] = $this->filterXPathForOdds($crawler, $quinella15XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['quinella'][1][6] = $this->filterXPathForOdds($crawler, $quinella16XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['quinella'][2][3] = $this->filterXPathForOdds($crawler, $quinella23XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['quinella'][2][4] = $this->filterXPathForOdds($crawler, $quinella24XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['quinella'][2][5] = $this->filterXPathForOdds($crawler, $quinella25XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['quinella'][2][6] = $this->filterXPathForOdds($crawler, $quinella26XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['quinella'][3][4] = $this->filterXPathForOdds($crawler, $quinella34XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['quinella'][3][5] = $this->filterXPathForOdds($crawler, $quinella35XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['quinella'][3][6] = $this->filterXPathForOdds($crawler, $quinella36XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['quinella'][4][5] = $this->filterXPathForOdds($crawler, $quinella45XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['quinella'][4][6] = $this->filterXPathForOdds($crawler, $quinella46XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['quinella'][5][6] = $this->filterXPathForOdds($crawler, $quinella56XPath);

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
    protected function crawlQuinellaPlace(Crawler $crawler, array $response, string $date, int $stadiumId, int $raceNumber): array
    {
        $quinellaPlace12XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[1]/td[2]', $this->baseXPath, $this->baseLevel + 6);
        $quinellaPlace13XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[2]/td[2]', $this->baseXPath, $this->baseLevel + 6);
        $quinellaPlace14XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[3]/td[2]', $this->baseXPath, $this->baseLevel + 6);
        $quinellaPlace15XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[4]/td[2]', $this->baseXPath, $this->baseLevel + 6);
        $quinellaPlace16XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[5]/td[2]', $this->baseXPath, $this->baseLevel + 6);
        $quinellaPlace23XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[2]/td[4]', $this->baseXPath, $this->baseLevel + 6);
        $quinellaPlace24XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[3]/td[4]', $this->baseXPath, $this->baseLevel + 6);
        $quinellaPlace25XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[4]/td[4]', $this->baseXPath, $this->baseLevel + 6);
        $quinellaPlace26XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[5]/td[4]', $this->baseXPath, $this->baseLevel + 6);
        $quinellaPlace34XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[3]/td[6]', $this->baseXPath, $this->baseLevel + 6);
        $quinellaPlace35XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[4]/td[6]', $this->baseXPath, $this->baseLevel + 6);
        $quinellaPlace36XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[5]/td[6]', $this->baseXPath, $this->baseLevel + 6);
        $quinellaPlace45XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[4]/td[8]', $this->baseXPath, $this->baseLevel + 6);
        $quinellaPlace46XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[5]/td[8]', $this->baseXPath, $this->baseLevel + 6);
        $quinellaPlace56XPath = sprintf('%s/div[2]/div[%s]/table/tbody/tr[5]/td[10]', $this->baseXPath, $this->baseLevel + 6);

        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['quinella_place'][1][2] = $this->filterXPathForOddsWithLowerLimitAndUpperLimit($crawler, $quinellaPlace12XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['quinella_place'][1][3] = $this->filterXPathForOddsWithLowerLimitAndUpperLimit($crawler, $quinellaPlace13XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['quinella_place'][1][4] = $this->filterXPathForOddsWithLowerLimitAndUpperLimit($crawler, $quinellaPlace14XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['quinella_place'][1][5] = $this->filterXPathForOddsWithLowerLimitAndUpperLimit($crawler, $quinellaPlace15XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['quinella_place'][1][6] = $this->filterXPathForOddsWithLowerLimitAndUpperLimit($crawler, $quinellaPlace16XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['quinella_place'][2][3] = $this->filterXPathForOddsWithLowerLimitAndUpperLimit($crawler, $quinellaPlace23XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['quinella_place'][2][4] = $this->filterXPathForOddsWithLowerLimitAndUpperLimit($crawler, $quinellaPlace24XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['quinella_place'][2][5] = $this->filterXPathForOddsWithLowerLimitAndUpperLimit($crawler, $quinellaPlace25XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['quinella_place'][2][6] = $this->filterXPathForOddsWithLowerLimitAndUpperLimit($crawler, $quinellaPlace26XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['quinella_place'][3][4] = $this->filterXPathForOddsWithLowerLimitAndUpperLimit($crawler, $quinellaPlace34XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['quinella_place'][3][5] = $this->filterXPathForOddsWithLowerLimitAndUpperLimit($crawler, $quinellaPlace35XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['quinella_place'][3][6] = $this->filterXPathForOddsWithLowerLimitAndUpperLimit($crawler, $quinellaPlace36XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['quinella_place'][4][5] = $this->filterXPathForOddsWithLowerLimitAndUpperLimit($crawler, $quinellaPlace45XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['quinella_place'][4][6] = $this->filterXPathForOddsWithLowerLimitAndUpperLimit($crawler, $quinellaPlace46XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['quinella_place'][5][6] = $this->filterXPathForOddsWithLowerLimitAndUpperLimit($crawler, $quinellaPlace56XPath);

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
    protected function crawlWin(Crawler $crawler, array $response, string $date, int $stadiumId, int $raceNumber): array
    {
        $win1XPath = sprintf('%s/div[2]/div[%s]/div[1]/div[2]/table/tbody[1]/tr/td[3]', $this->baseXPath, $this->baseLevel + 5);
        $win2XPath = sprintf('%s/div[2]/div[%s]/div[1]/div[2]/table/tbody[2]/tr/td[3]', $this->baseXPath, $this->baseLevel + 5);
        $win3XPath = sprintf('%s/div[2]/div[%s]/div[1]/div[2]/table/tbody[3]/tr/td[3]', $this->baseXPath, $this->baseLevel + 5);
        $win4XPath = sprintf('%s/div[2]/div[%s]/div[1]/div[2]/table/tbody[4]/tr/td[3]', $this->baseXPath, $this->baseLevel + 5);
        $win5XPath = sprintf('%s/div[2]/div[%s]/div[1]/div[2]/table/tbody[5]/tr/td[3]', $this->baseXPath, $this->baseLevel + 5);
        $win6XPath = sprintf('%s/div[2]/div[%s]/div[1]/div[2]/table/tbody[6]/tr/td[3]', $this->baseXPath, $this->baseLevel + 5);

        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['win'][1] = $this->filterXPathForOdds($crawler, $win1XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['win'][2] = $this->filterXPathForOdds($crawler, $win2XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['win'][3] = $this->filterXPathForOdds($crawler, $win3XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['win'][4] = $this->filterXPathForOdds($crawler, $win4XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['win'][5] = $this->filterXPathForOdds($crawler, $win5XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['win'][6] = $this->filterXPathForOdds($crawler, $win6XPath);

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
    protected function crawlPlace(Crawler $crawler, array $response, string $date, int $stadiumId, int $raceNumber): array
    {
        $place1XPath = sprintf('%s/div[2]/div[%s]/div[2]/div[2]/table/tbody[1]/tr/td[3]', $this->baseXPath, $this->baseLevel + 5);
        $place2XPath = sprintf('%s/div[2]/div[%s]/div[2]/div[2]/table/tbody[2]/tr/td[3]', $this->baseXPath, $this->baseLevel + 5);
        $place3XPath = sprintf('%s/div[2]/div[%s]/div[2]/div[2]/table/tbody[3]/tr/td[3]', $this->baseXPath, $this->baseLevel + 5);
        $place4XPath = sprintf('%s/div[2]/div[%s]/div[2]/div[2]/table/tbody[4]/tr/td[3]', $this->baseXPath, $this->baseLevel + 5);
        $place5XPath = sprintf('%s/div[2]/div[%s]/div[2]/div[2]/table/tbody[5]/tr/td[3]', $this->baseXPath, $this->baseLevel + 5);
        $place6XPath = sprintf('%s/div[2]/div[%s]/div[2]/div[2]/table/tbody[6]/tr/td[3]', $this->baseXPath, $this->baseLevel + 5);

        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['place'][1] = $this->filterXPathForOddsWithLowerLimitAndUpperLimit($crawler, $place1XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['place'][2] = $this->filterXPathForOddsWithLowerLimitAndUpperLimit($crawler, $place2XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['place'][3] = $this->filterXPathForOddsWithLowerLimitAndUpperLimit($crawler, $place3XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['place'][4] = $this->filterXPathForOddsWithLowerLimitAndUpperLimit($crawler, $place4XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['place'][5] = $this->filterXPathForOddsWithLowerLimitAndUpperLimit($crawler, $place5XPath);
        $response['stadiums'][$stadiumId]['races'][$raceNumber]['oddses']['place'][6] = $this->filterXPathForOddsWithLowerLimitAndUpperLimit($crawler, $place6XPath);

        return $response;
    }
}
