<?php

namespace App\Actions\SeoGen;

use App\Actions\ChatGPT\ChatGPTClient;
use App\Actions\Serpstat\SerpstatClient;

class SegmentDataGenerator extends DataGenerator
{
    public function handle($attendee = '', $city = '', $venue = '', $date = '', $segment = '')
    {
        return $this->generate($attendee, $city, $venue, $date, $segment, 'segment');
    }

    protected function getKeywordsFromSerpstat($keyword, $city = '', $additionalKeywords = [], $segment = '', $venue = '')
    {
        $this->initialKeywords = SerpstatClient::run($city . ' ' . $segment . ' Events', $city, $additionalKeywords);
        $this->flattenedKeywords = $this->flattenKeywords($this->initialKeywords);
        $this->flattenedKeywords = array_slice($this->flattenedKeywords, 0, self::LIMIT_FROM_SERPSTAT);
    }

    protected function prepareInitialKeywords($attendee, $city, $venue, $segment = '')
    {
        return [
            $city . ' ' . $segment . ' Buy Tickets',
            $segment . ' Tickets in ' . $city,
            $segment . ' Concerts in ' . $city,
        ];
    }

    protected function defaultKeywords($attendee, $city, $venue, $segment = ''): array
    {
        return [
            $city . ' ' . $segment . ' Buy Tickets',
            $segment . ' Events',
            $segment . ' Tickets in ' . $city,
            $segment . ' Concerts in ' . $city,
        ];
    }
}
