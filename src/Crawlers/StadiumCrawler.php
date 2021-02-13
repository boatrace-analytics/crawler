<?php

namespace Boatrace\Analytics\Crawlers;

use Goutte\Client as Goutte;
use Boatrace\Analytics\Converter;
use Carbon\CarbonImmutable as Carbon;

/**
 * @author shimomo
 */
class StadiumCrawler extends BaseCrawler
{
    /**
     * @param  \Goutte\Client  $goutte
     * @return void
     */
    public function __construct(Goutte $goutte)
    {
        parent::__construct($goutte);
    }

    /**
     * @param  string  $date
     * @param  int     $seconds
     * @return array
     */
    public function crawlStadiumId(string $date, int $seconds): array
    {
        $response = [];

        $boatraceDate = Carbon::parse($date)->format('Ymd');

        $crawlerFormat = '%s/owpc/pc/race/index?hd=%s';
        $crawlerUrl = sprintf($crawlerFormat, $this->baseUrl, $boatraceDate);
        $crawler = $this->goutte->request('GET', $crawlerUrl);
        sleep($seconds);

        $crawler->filter('table tbody td.is-arrow1.is-fBold.is-fs15')->each(function ($element) use (&$response) {
            $response[] = Converter::convertToStadiumId(str_replace('>', '', $element->filter('a')->filter('img')->attr('alt')));
        });

        return $response;
    }

    /**
     * @param  string  $date
     * @param  int     $seconds
     * @return array
     */
    public function crawlStadiumName(string $date, int $seconds): array
    {
        $response = [];

        $boatraceDate = Carbon::parse($date)->format('Ymd');

        $crawlerFormat = '%s/owpc/pc/race/index?hd=%s';
        $crawlerUrl = sprintf($crawlerFormat, $this->baseUrl, $boatraceDate);
        $crawler = $this->goutte->request('GET', $crawlerUrl);
        sleep($seconds);

        $crawler->filter('table tbody td.is-arrow1.is-fBold.is-fs15')->each(function ($element) use (&$response) {
            $response[] = str_replace('>', '', $element->filter('a')->filter('img')->attr('alt'));
        });

        return $response;
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
        return [];
    }
}
