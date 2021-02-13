<?php

namespace Boatrace\Analytics\Crawlers;

use Goutte\Client as Goutte;
use Boatrace\Analytics\Converter;
use Carbon\CarbonImmutable as Carbon;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @author shimomo
 */
abstract class BaseCrawler
{
    /**
     * @var \Goutte\Client
     */
    protected $goutte;

    /**
     * @var string
     */
    protected $baseUrl = 'https://www.boatrace.jp';

    /**
     * @param  \Goutte\Client  $goutte
     * @return void
     */
    protected function __construct(Goutte $goutte)
    {
        $this->goutte = $goutte;
    }

    /**
     * @param  \Symfony\Component\DomCrawler\Crawler  $crawler
     * @param  string                                 $xpath
     * @return string|null
     */
    protected function filterXPath(Crawler $crawler, string $xpath): ?string
    {
        return count($crawler->filterXPath($xpath)) ? Converter::convertToString($crawler->filterXPath($xpath)->text()) : null;
    }

    /**
     * @param  \Symfony\Component\DomCrawler\Crawler  $crawler
     * @param  string                                 $xpath
     * @return float|null
     */
    protected function filterXPathForOdds(Crawler $crawler, string $xpath): ?float
    {
        return count($crawler->filterXPath($xpath)) ? Converter::convertToFloat($crawler->filterXPath($xpath)->text()) : null;
    }

    /**
     * @param  \Symfony\Component\DomCrawler\Crawler  $crawler
     * @param  string                                 $xpath
     * @return array
     */
    protected function filterXPathForOddsWithLowerLimitAndUpperLimit(Crawler $crawler, string $xpath): array
    {
        $response = [];

        if (count($crawler->filterXPath($xpath))) {
            if (count($oddses = explode('-', $crawler->filterXPath($xpath)->text())) === 2) {
                $lowerLimit = Converter::convertToFloat(array_shift($oddses));
                $upperLimit = Converter::convertToFloat(array_shift($oddses));
            }
        }

        $response['lower_limit'] = $lowerLimit ?? null;
        $response['upper_limit'] = $upperLimit ?? null;

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
    abstract protected function crawl(array $response, string $date, int $stadiumId, int $raceNumber, int $seconds): array;
}
