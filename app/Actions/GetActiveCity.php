<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use Stevebauman\Location\Facades\Location;

class GetActiveCity
{
    use AsAction;

    public $location;

    public $type;

    private $cities;

    private $place;

    public function handle()
    {
        $this->getCities();
        $this->getLocation();
        if ($this->isCurrentUserHasCity()) {
            $this->type = 'city';
            $this->place = $this->location->cityName;
        } elseif ($this->isCurrentUserHasState()) {
            $this->type = 'state';
            $this->place = $this->location->regionName;
        } else {
            $this->type = 'all';
            $this->place = 'All Cities';
        }

        return [
            'user_location' => request()->cookie('user_location')
                    ?? $this->location->cityName
                    ?? $this->location->regionName
                    ?? 'All Cities',
            'user_location_type' => request()->cookie('user_location_type') ?? $this->type,
        ];
    }

    private function getLocation()
    {
        $ip = request()->ip();
//        $ip = '48.188.144.248'; /* Static IP address */
        $this->location = Location::get($ip);
    }

    private function getCities()
    {
        $this->cities = GetCities::run();
    }

    private function isCurrentUserHasCity()
    {
        foreach ($this->cities as $state => $cities) {
            if (in_array($this->location->cityName, $cities)) {
                return true;
            }
        }
        return false;
    }

    private function isCurrentUserHasState()
    {
        return in_array($this->location->regionName, $this->cities);
    }
}
