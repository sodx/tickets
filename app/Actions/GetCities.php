<?php

namespace App\Actions;

use App\Models\Event;
use App\Models\Venue;
use Lorisleiva\Actions\Concerns\AsAction;

class GetCities
{
    use AsAction;

    public $title;

    public $description;

    private $venues;

    public function handle()
    {
        $this->getAllVenues();
        return $this->getVenueCityAndStateFromEvents();
    }

    private function getAllVenues()
    {
        $this->venues = Venue::all();
    }

    private function getVenueCityAndStateFromEvents()
    {
        $output = [];
        foreach ($this->venues as $venue) {
            if( !isset($output[$venue->state]) ) {
                $output[$venue->state] = [];
            }
            if(!in_array($venue->city, $output[$venue->state])) {
                $output[$venue->state][] = $venue->city;
            }
        }
        ksort($output);
        return $output;
    }
}
