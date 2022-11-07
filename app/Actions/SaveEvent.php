<?php

namespace App\Actions;

use App\Models\Event;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Actions\GenerateSeoMeta;

class SaveEvent
{
    use AsAction;

    public function handle($event): void
    {
        $this->upsertEvent($event);
    }

    public function upsertEvent(array $event)
    {
        Event::updateOrCreate(
            ['ticketmaster_id' => $event['id']],
            [
                'name' => $event['name'] ?? '',
                'url' => $event['url'] ?? '',
                'locale' => $event['locale'] ?? '',
                'start_date' => $event['dates']['start']['localDate'] ?? '',
                'start_time' => $event['dates']['start']['localTime'] ?? '',
                'end_date' => $event['dates']['end']['localDate'] ?? null,
                'end_time' => $event['dates']['end']['localTime'] ?? null,
                'price_min' => $event['priceRanges'][0]['min'] ?? null,
                'price_max' => $event['priceRanges'][0]['max'] ?? null,
                'price_currency' => $event['priceRanges'][0]['currency'] ?? '',
                'status' => $event['dates']['status']['code'] ?? '',
                'slug' => SlugService::createSlug(Event::class, 'slug', $event['name']),
                'thumbnail' => $this->getSmallestImage($event['images']),
                'poster' => $this->getBiggestImage($event['images']),
                'seatmap' => $event['seatmap']['staticUrl'] ?? '',
                'info' => $event['info'] ?? '',
                'pleaseNote' => $event['pleaseNote'] ?? '',
                'meta_title' => $this->generateTitle($event),
                'meta_keywords' => '',
                'meta_description' => $this->generateDescription($event),
                'clicks' => '0',
                'views' => '0',
            ]
        );
    }

    /**
     * Get the biggest image from array of images.
     *
     * @param array $images
     * @return string
     */
    private function getBiggestImage(array $images): string
    {
        $biggestImage = $images[0];
        foreach ($images as $image) {
            if ($image['width'] > $biggestImage['width']) {
                $biggestImage = $image;
            }
        }
        return $biggestImage['url'];
    }

    /**
     * Get the smallest image from array of images.
     *
     * @param array $images
     * @return string
     */
    private function getSmallestImage(array $images): string
    {
        $smallestImage = $images[0];
        foreach ($images as $image) {
            if ($image['width'] < $smallestImage['width']) {
                $smallestImage = $image;
            }
        }
        return $smallestImage['url'];
    }


    public function generateTitle($event): string
    {
        return $event['name'];
    }


    public function generateDescription($event): string
    {
        return $event['name'];
    }
}
