<?php

namespace App\Actions;

use App\Actions\SeoGen\SeoGen;
use App\Models\City;
use App\Models\CitySegment;
use App\Models\Segment;
use Cviebrock\EloquentSluggable\Services\SlugService;

class SaveCity extends SaveDataFromTM
{
    public function upsertData(array $data): City
    {
        if (!City::where('name', $data['city'])->exists()) {
            $generatedMeta = SeoGen::run('city', '', $data['city'], '', '', '');
            $city = City::create(
                [
                    'name' => $data['city'] ?? '',
                    'seo_title' => $generatedMeta['data']['title'] ?? '',
                    'seo_keywords' => $generatedMeta['keywords'] ?? '',
                    'seo_description' => $generatedMeta['data']['meta_description'] ?? '',
                    'seo_content' => $generatedMeta['data']['content'] ?? '',
                ]
            );
        } else {
            $city = City::where('name', $data['city'])->first();
        }
        return $city;
    }
}
