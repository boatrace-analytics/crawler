<?php

namespace Boatrace\Analytics;

use DI\Container;
use DI\ContainerBuilder;
use Illuminate\Support\Collection;
use Boatrace\Analytics\Crawlers\BaseCrawler;

/**
 * @author shimomo
 */
class MainCrawler
{
    /**
     * @var array
     */
    protected $instances;

    /**
     * @var \DI\Container
     */
    protected $container;

    /**
     * @return void
     */
    public function __construct()
    {
        Collection::macro('recursive', function () {
            return $this->map(function ($value) {
                return is_array($value) || is_object($value) ? collect($value)->recursive() : $value;
            });
        });
    }

    /**
     * @param  string  $name
     * @param  array   $arguments
     * @return \Illuminate\Support\Collection
     */
    public function __call(string $name, array $arguments): Collection
    {
        return call_user_func_array([$this, 'crawl'], array_merge([mb_strtolower(str_replace('crawl', '', $name))], $arguments));
    }

    /**
     * @param  string    $name
     * @param  string    $date
     * @param  int|null  $stadiumId
     * @param  int|null  $raceNumber
     * @param  int|null  $seconds
     * @return \Illuminate\Support\Collection
     */
    public function crawl(string $name, string $date, int $stadiumId = null, int $raceNumber = null, int $seconds = null): Collection
    {
        if (is_null($seconds) || $seconds < 0) {
            $seconds = 1;
        }

        if (is_null($stadiumId) && is_null($raceNumber)) {
            return collect($this->crawlWithoutStadiumIdRaceNumber($this->getCrawler($name), $date, $seconds))->recursive();
        }

        if (is_null($stadiumId)) {
            return collect($this->crawlWithoutStadiumId($this->getCrawler($name), $date, $raceNumber, $seconds))->recursive();
        }

        if (is_null($raceNumber)) {
            return collect($this->crawlWithoutRaceNumber($this->getCrawler($name), $date, $stadiumId, $seconds))->recursive();
        }

        return collect($this->getCrawler($name)->crawl([], $date, $stadiumId, $raceNumber, $seconds))->recursive();
    }

    /**
     * @param  \Boatrace\Analytics\BaseCrawler  $crawler
     * @param  string                           $date
     * @param  int                              $seconds
     * @return array
     */
    protected function crawlWithoutStadiumIdRaceNumber(BaseCrawler $crawler, string $date, int $seconds): array
    {
        $response = [];

        foreach ($this->getCrawler('stadium')->crawlStadiumId($date, $seconds) as $stadiumId) {
            foreach (range(1, 12) as $raceNumber) {
                $response = $crawler->crawl($response, $date, $stadiumId, $raceNumber, $seconds);
            }
        }

        return $response;
    }

    /**
     * @param  \Boatrace\Analytics\BaseCrawler  $crawler
     * @param  string                           $date
     * @param  int                              $raceNumber
     * @param  int                              $seconds
     * @return array
     */
    protected function crawlWithoutStadiumId(BaseCrawler $crawler, string $date, int $raceNumber, int $seconds): array
    {
        $response = [];

        foreach ($this->getCrawler('stadium')->crawlStadiumId($date, $seconds) as $stadiumId) {
            $response = $crawler->crawl($response, $date, $stadiumId, $raceNumber, $seconds);
        }

        return $response;
    }

    /**
     * @param  \Boatrace\Analytics\BaseCrawler  $crawler
     * @param  string                           $date
     * @param  int                              $stadiumId
     * @param  int                              $seconds
     * @return array
     */
    protected function crawlWithoutRaceNumber(BaseCrawler $crawler, string $date, int $stadiumId, int $seconds): array
    {
        $response = [];

        foreach (range(1, 12) as $raceNumber) {
            $response = $crawler->crawl($response, $date, $stadiumId, $raceNumber, $seconds);
        }

        return $response;
    }

    /**
     * @param  string  $name
     * @return \Boatrace\Analytics\BaseCrawler
     */
    public function getCrawler(string $name): BaseCrawler
    {
        return $this->instances[$name] ?? $this->instances[$name] = (
            $this->container ?? $this->container = $this->getContainer()
        )->get(ucfirst($name) . 'Crawler');
    }

    /**
     * @return \DI\Container
     */
    public function getContainer(): Container
    {
        $builder = new ContainerBuilder;

        $builder->addDefinitions(__DIR__ . '/../config/definitions.php');

        return $builder->build();
    }
}
