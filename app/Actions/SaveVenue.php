<?php

namespace App\Actions;

use App\Models\Venue;
use Cviebrock\EloquentSluggable\Services\SlugService;

class SaveVenue extends SaveDataFromTM
{
    public function upsertData(array $data): Venue
    {
        $venueModel = new Venue();
        if ($this->shouldUpdateItem($data, $venueModel)) {
            $venue = Venue::updateOrCreate(
                ['ticketmaster_id' => $data['id']],
                [
                    'name' => $data['name'] ?? '',
                    'url' => $data['url'] ?? '',
                    'locale' => $data['locale'] ?? '',
                    'postcode' => $data['postalCode'] ?? '',
                    'timezone' => $data['timezone'] ?? '',
                    'city' => $data['city']['name'] ?? '',
                    'state' => $data['state']['name'] ?? '',
                    'country' => $data['country']['name'] ?? '',
                    'country_code' => $data['country']['countryCode'] ?? '',
                    'state_code' => $data['state']['stateCode'] ?? '',
                    'address' => $data['address']['line1'] ?? '',
                    'longtitude' => $data['location']['longitude'] ?? '',
                    'latitude' => $data['location']['latitude'] ?? '',
                    'image' => $data['images'][0]['url'] ?? '',
                    'slug' => SlugService::createSlug(Venue::class, 'slug', $data['name']),
                ]
            );
        } else {
            $venue = Venue::where('ticketmaster_id', $data['id'])->first();
        }
        return $venue;
    }

}
