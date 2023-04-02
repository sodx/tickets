<?php

namespace App\Actions\SeoGen;

use App\Actions\ChatGPT\ChatGPTClient;

class EventDataGenerator extends DataGenerator
{
    public function handle($attendee = '', $city = '', $venue = '', $date = '')
    {
        return $this->generate($attendee, $city, $venue, $date);
    }

    protected function defaultKeywords($attendee, $city, $venue, $segment = ''): array
    {
        return [
            $attendee . ' Buy Tickets',
            $attendee . ' Buy tickets in ' . $city,
            $attendee . ' in ' . $venue,
            $attendee . ' in ' . $city,
            'Buy tickets for ' . $attendee,
            'Buy tickets for ' . $attendee . ' in ' . $city,
            'Tickets ' . $attendee,
            'Concert ' . $attendee,
        ];
    }
}
