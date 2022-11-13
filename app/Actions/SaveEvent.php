<?php

namespace App\Actions;

use App\Models\Attraction;
use App\Models\Event;
use App\Models\Venue;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Actions\GenerateSeoMeta;

class SaveEvent extends SaveDataFromTM
{
    public function upsertData(array $data): Event
    {
        $venue = $this->saveVenue($data['_embedded']['venues'][0]);
        $attractions = $this->saveAttractions($data['_embedded']['attractions']);
        return Event::updateOrCreate(
            ['ticketmaster_id' => $data['id']],
            [
                'name' => $data['name'] ?? '',
                'url' => $data['url'] ?? '',
                'locale' => $data['locale'] ?? '',
                'start_date' => $data['dates']['start']['localDate'] ?? '',
                'start_time' => $data['dates']['start']['localTime'] ?? '',
                'end_date' => $data['dates']['end']['localDate'] ?? null,
                'end_time' => $data['dates']['end']['localTime'] ?? null,
                'price_min' => $data['priceRanges'][0]['min'] ?? null,
                'price_max' => $data['priceRanges'][0]['max'] ?? null,
                'price_currency' => $data['priceRanges'][0]['currency'] ?? '',
                'status' => $data['dates']['status']['code'] ?? '',
                'slug' => SlugService::createSlug(Event::class, 'slug', $data['name']),
                'thumbnail' => $this->getSmallestImage($data['images']),
                'poster' => $this->getBiggestImage($data['images']),
                'seatmap' => $data['seatmap']['staticUrl'] ?? '',
                'info' => $data['info'] ?? '',
                'pleaseNote' => $data['pleaseNote'] ?? '',
                'meta_title' => $this->generateTitle($data),
                'meta_keywords' => '',
                'meta_description' => $this->generateDescription($data),
                'clicks' => '0',
                'views' => '0',
                'venue_id' => $venue->venue_id,
            ]
        );
    }

    /**
     * @param array $venue
     */
    public function saveVenue(array $venue): Venue
    {
        return SaveVenue::run($venue);
    }

    /**
     * @param array $attractions
     */
    public function saveAttractions(array $attractions)
    {
        return array_map(function ($attraction) {
            return SaveAttraction::run($attraction);
        }, $attractions);
    }

}
