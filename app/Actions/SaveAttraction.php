<?php

namespace App\Actions;

use App\Models\Attraction;
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
                    'thumbnail' => $this->getSmallestImage($data['images']) ?? '',
                    'poster' => $this->getBiggestImage($data['images']) ?? '',
                    'slug' => SlugService::createSlug(Attraction::class, 'slug', $data['name']) ?? '',
                ]
            );
        } else {
            $attraction = Attraction::where('ticketmaster_id', $data['id'])->first();
        }
        return $attraction;
    }
}
