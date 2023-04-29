<?php

namespace App\Actions\SeoGen;

use App\Actions\ChatGPT\ChatGPTClient;

class AttendeeDataGenerator extends DataGenerator
{
    public function handle($attendee = '', $city = '', $venue = '', $date = '', $segment = '')
    {
        return $this->generate($attendee, $city, $venue, $date, $segment, 'attendee');
    }

    protected function prepareInitialKeywords($attendee, $city, $venue, $segment = '')
    {
        return [
            $attendee . ' Buy Tickets',
            'Events ' . $attendee,
            'Concerts ' . $attendee,
            'Tickets ' . $attendee,
            'Buy tickets for ' . $attendee,
        ];
    }

    protected function defaultKeywords($attendee, $city, $venue, $segment = ''): array
    {
        return [
            $attendee . ' Buy Tickets',
            'Events ' . $attendee,
            'Concerts ' . $attendee,
            'Tickets ' . $attendee,
            'Buy tickets for ' . $attendee,
        ];
    }
}
