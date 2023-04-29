<?php

namespace App\Actions\SeoGen;

use App\Actions\ChatGPT\ChatGPTClient;
use App\Actions\Serpstat\SerpstatClient;

class VenueDataGenerator extends DataGenerator
{
    public function handle($attendee = '', $city = '', $venue = '', $date = '', $segment = '')
    {
        return $this->generate($attendee, $city, $venue, $date, $segment, 'venue');
    }

    protected function getKeywordsFromSerpstat($keyword, $city = '', $additionalKeywords = [], $segment = '', $venue = '')
    {
        $this->initialKeywords = SerpstatClient::run($venue . ' Events', $city, $additionalKeywords);
        $this->flattenedKeywords = $this->flattenKeywords($this->initialKeywords);
        $this->flattenedKeywords = array_slice($this->flattenedKeywords, 0, self::LIMIT_FROM_SERPSTAT);
    }

    protected function prepareInitialKeywords($attendee, $city, $venue, $segment = '')
    {
        return [
            $venue . ' Buy Tickets',
            'Tickets in ' . $venue,
            'Concerts in ' . $venue,
        ];
    }

    protected function defaultKeywords($attendee, $city, $venue, $segment = ''): array
    {
        return [
            $venue . ' Buy Tickets',
            'Tickets in ' . $venue,
            'Concerts in ' . $venue,
            'Buy tickets in ' . $venue,
            'Events in ' . $venue,
            $venue . ' Concerts',
            $venue . ' Events',
        ];
    }
}
