<?php

namespace App\Actions\SeoGen;

use App\Actions\ChatGPT\ChatGPTClient;
use App\Actions\Serpstat\SerpstatClient;
use GuzzleHttp\Client;
use Lorisleiva\Actions\Concerns\AsAction;

class SeoGen
{
    use AsAction;

    public function handle($type = 'event', $attendee = '', $city = '', $venue = '', $date = '', $segment = '')
    {
        switch ($type) {
            case 'event':
                $data = EventDataGenerator::run($attendee, $city, $venue, $date);
                break;
            case 'attendee':
                $data = AttendeeDataGenerator::run($attendee, $city, $venue, $date);
                break;
            case 'venue':
                $data = VenueDataGenerator::run($attendee, $city, $venue, $date);
                break;
            case 'city':
                $data = CityDataGenerator::run('', $city, '', '');
                break;
            case 'segment':
                $data = SegmentDataGenerator::run($attendee, $city, $venue, $date, $segment);
                break;
            default:
                $data = [];
                break;
        }
        return $data;
    }
}
