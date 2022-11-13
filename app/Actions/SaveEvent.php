<?php

namespace App\Actions;

use App\Models\Event;
use App\Models\Genre;
use App\Models\Segment;
use App\Models\Subgenre;
use App\Models\Venue;
use Cviebrock\EloquentSluggable\Services\SlugService;
use App\Actions\GenerateSeoMeta;

class SaveEvent extends SaveDataFromTM
{
    public function upsertData(array $data): Event
    {
        $venue = $this->saveVenue($data['_embedded']['venues'][0]);
        $attractions = $this->saveAttractions($data['_embedded']['attractions']);
        $event = Event::updateOrCreate(
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

        $event->attractions()->sync($attractions->pluck('attraction_id'));
        $event->subgenre()->associate($this->saveSubgenre($data['classifications'][0]['subGenre']));
        $event->genre()->associate($this->saveGenre($data['classifications'][0]['genre']));
        $event->segment()->associate($this->saveSegment($data['classifications'][0]['segment']));
        $event->save();

        return $event;
    }

    /**
     * @param array $venue
     */
    public function saveVenue(array $venue): Venue
    {
        return SaveVenue::run($venue);
    }


    public function saveGenre(array $genre): Genre
    {
        return SaveGenre::run($genre);
    }

    public function saveSubgenre(array $subgenre): Subgenre
    {
        return SaveSubgenre::run($subgenre);
    }

    public function saveSegment(array $segment): Segment
    {
        return SaveSegment::run($segment);
    }

    /**
     * @param array $attractions
     */
    public function saveAttractions(array $attractions)
    {
        return collect(array_map(function ($attraction) {
            return SaveAttraction::run($attraction);
        }, $attractions));
    }

}
