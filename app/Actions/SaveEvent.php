<?php

namespace App\Actions;

use App\Actions\GenerateSeoMeta;
use App\Actions\SeoGen\SeoGen;
use App\Models\City;
use App\Models\CitySegment;
use App\Models\Event;
use App\Models\Genre;
use App\Models\Segment;
use App\Models\Subgenre;
use App\Models\Venue;
use App\Models\Tour;
use Cviebrock\EloquentSluggable\Services\SlugService;

class SaveEvent extends SaveDataFromTM
{
    public function upsertData(array $data): Event | null
    {
        if (!isset($data['_embedded']['attractions'])) {
            return null;
        }

        $info = GenerateSeoMeta::run($data);
        $info = isset($info['info']) ? $info['info'] : '';
        $info = isset($data['info']) ? $info . ' ' . $data['info'] : $info;

        $venue = $this->saveVenue($data['_embedded']['venues'][0]);
        $attractions = $this->saveAttractions($data['_embedded']['attractions']);

        if (!isset($data['name'])) {
            return null;
        }
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
                'medium_image' => $this->getMediumImage($data['images']),
                'seatmap' => $data['seatmap']['staticUrl'] ?? '',
                'info' => $info,
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
        if (isset($data['classifications'][0]['subGenre'])) {
            $event->subgenre()->associate($this->saveSubgenre($data['classifications'][0]['subGenre']));
        }
        if (isset($data['classifications'][0]['genre'])) {
            $event->genre()->associate($this->saveGenre($data['classifications'][0]['genre']));
            $data['classifications'][0]['genre']['city'] = $venue->city;
            $this->saveCitySegment($data['classifications'][0]['genre']);
        }
        if (isset($data['classifications'][0]['segment'])) {
            $event->segment()->associate($this->saveSegment($data['classifications'][0]['segment']));
            $data['classifications'][0]['segment']['city'] = $venue->city;
            $this->saveCitySegment($data['classifications'][0]['segment']);
        }

        $this->saveCity(['city' => $venue->city]);
        $event->tour()->associate($this->saveTour($data));
        $generatedMeta = SeoGen::run('event', $data['name'], $venue->city, $venue->name, $data['dates']['start']['localDate']);
        $event->meta_title = $generatedMeta['data']['title'] ?? '';
        $event->meta_description = $generatedMeta['data']['meta_description'] ?? '';
        $event->meta_keywords = $generatedMeta['keywords'] ?? '';
        $event->info = $generatedMeta['data']['content'] ?? '';
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

    public function saveCitySegment(array $segment): CitySegment
    {
        return SaveCitySegment::run($segment);
    }

    public function saveCity(array $city): City
    {
        return SaveCity::run($city);
    }

    public function saveTour(array $event): Tour | null
    {
        $sameEvent = Event::where('name', $event['name'])->first();
        if ($sameEvent) {
            $tour = SaveTour::run($event);
            if (!$sameEvent->tour_id) {
                $sameEvent->tour()->associate($tour);
            }

            return $tour;
        }

        return null;
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
