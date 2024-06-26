<?php

namespace App\Actions;

use App\Actions\SeoGen\SeoGen;
use App\Models\Venue;
use Cviebrock\EloquentSluggable\Services\SlugService;

class SaveVenue extends SaveDataFromTM
{
    public function upsertData(array $data): Venue
    {
        $venueModel = new Venue();
        if ($this->shouldUpdateItem($data, $venueModel)) {
            $generatedMeta = SeoGen::run('venue', $data['name'], $data['city']['name'], '', '');
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
                    'seo_title' => $generatedMeta['data']['title'] ?? '',
                    'seo_keywords' => $generatedMeta['keywords'] ?? '',
                    'seo_description' => $generatedMeta['data']['meta_description'] ?? '',
                    'seo_content' => $generatedMeta['data']['content'] ?? '',
                ]
            );
        } else {
            $venue = Venue::where('ticketmaster_id', $data['id'])->first();
        }
        return $venue;
    }
}
