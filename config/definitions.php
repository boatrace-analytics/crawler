<?php

return [
    'Crawler' => \DI\create('\Boatrace\Analytics\Crawler')->constructor(
        \DI\get('MainCrawler')
    ),
    'MainCrawler' => function ($container) {
        return $container->get('\Boatrace\Analytics\MainCrawler');
    },
    'NoticeCrawler' => \DI\create('\Boatrace\Analytics\Crawlers\NoticeCrawler')->constructor(
        \DI\get('Goutte')
    ),
    'OddsCrawler' => \DI\create('\Boatrace\Analytics\Crawlers\OddsCrawler')->constructor(
        \DI\get('Goutte')
    ),
    'ProgramCrawler' => \DI\create('\Boatrace\Analytics\Crawlers\ProgramCrawler')->constructor(
        \DI\get('Goutte')
    ),
    'ResultCrawler' => \DI\create('\Boatrace\Analytics\Crawlers\ResultCrawler')->constructor(
        \DI\get('Goutte')
    ),
    'StadiumCrawler' => \DI\create('\Boatrace\Analytics\Crawlers\StadiumCrawler')->constructor(
        \DI\get('Goutte')
    ),
    'Goutte' => function ($container) {
        return $container->get('\Goutte\Client');
    },
];
