<?php

namespace App\Actions;

use App\Models\Attraction;
use App\Models\Event;
use Cviebrock\EloquentSluggable\Services\SlugService;

class SaveAttraction extends SaveDataFromTM
{
    public function upsertData(array $data): Attraction
    {
        $attractionModel = new Attraction();
        if ($this->shouldUpdateItem($data, $attractionModel)) {
            $attraction = Attraction::updateOrCreate(
                ['ticketmaster_id' => $data['id']],
                [
                    'name' => $data['name'] ?? '',
                    'locale' => $data['locale'] ?? '',
                    'type' => $data['type'] ?? '',
                    'youtube_link' => $data['externalLinks']['youtube'][0]['url'] ?? '',
                    'twitter' => $data['externalLinks']['twitter'][0]['url'] ?? '',
                    'itunes' => $data['externalLinks']['itunes'][0]['url'] ?? '',
                    'lastfm' => $data['externalLinks']['lastfm'][0]['url'] ?? '',
                    'wiki' => $data['externalLinks']['wiki'][0]['url'] ?? '',
                    'facebook' => $data['externalLinks']['facebook'][0]['url'] ?? '',
                    'homepage' => $data['externalLinks']['homepage'][0]['url'] ?? '',
                    'instagram' => $data['externalLinks']['instagram'][0]['url'] ?? '',
                    'thumbnail' => isset($data['images']) ? $this->getSmallestImage($data['images']) : '',
                    'poster' => isset($data['images']) ? $this->getBiggestImage($data['images']) : '',
                    'medium_image' => isset($data['images']) ? $this->getMediumImage($data['images']) : '',
                    'video_ids' => isset($data['externalLinks']['youtube']) ?
                        $this->getVideoIds($data['externalLinks']['youtube'][0]['url'], $data['name']) : '',
                    'slug' => isset($data['name'])
                        ? SlugService::createSlug(Attraction::class, 'slug', $data['name'])
                        : '',
                ]
            );
        } else {
            $attraction = Attraction::where('ticketmaster_id', $data['id'])->first();
        }
        return $attraction;
    }

    /**
     * Method to receive most viewed youtube videos by providen playlist url using youtube API.
     */
    public function getVideoIds($youtubeUrl, $attractionName)
    {
        if (empty($youtubeUrl)) {
            return '';
        }
        $events = $this->getEventsByAttractionName($attractionName);
        if ($events->count() > 4) {
            return '';
        }
        return GetYoutubeVideosByURL::run($youtubeUrl);
    }

    public function getEventsByAttractionName($attractionName)
    {
        return Event::whereHas('attraction', function ($query) use ($attractionName) {
            $query->where('name', 'LIKE', "%{$attractionName}%");
        })->get();
    }
}
