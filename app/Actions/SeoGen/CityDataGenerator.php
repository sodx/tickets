<?php

namespace App\Actions\SeoGen;

use App\Actions\ChatGPT\ChatGPTClient;
use App\Actions\Serpstat\SerpstatClient;

class CityDataGenerator extends DataGenerator
{
    public function handle($attendee = '', $city = '', $venue = '', $date = '')
    {
        return $this->generate($attendee, $city, $venue, $date, '', 'city');
    }

    protected function getKeywordsFromSerpstat($keyword, $city = '', $additionalKeywords = [], $segment = '', $venue = '')
    {
        $this->initialKeywords = SerpstatClient::run($city . ' Events', $city, $additionalKeywords);
        $this->flattenedKeywords = $this->flattenKeywords($this->initialKeywords);
        $this->flattenedKeywords = array_slice($this->flattenedKeywords, 0, self::LIMIT_FROM_SERPSTAT);
    }

    protected function prepareInitialKeywords($attendee, $city, $venue, $segment = '')
    {
        return [
            $city . ' Buy Tickets',
            'Tickets in ' . $city,
            'Concerts in ' . $city,
        ];
    }

    protected function defaultKeywords($attendee, $city, $venue, $segment = ''): array
    {
        return [
            $city . ' Buy Tickets',
            'Tickets in ' . $city,
            'Concerts in ' . $city,
            'Buy tickets in ' . $city,
            'Events in ' . $city,
            $city . ' Concerts',
            $city . ' Events',
        ];
    }
}
