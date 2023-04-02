<?php

namespace App\Actions;

use App\Actions\SeoGen\SeoGen;
use App\Models\CitySegment;
use App\Models\Segment;
use Cviebrock\EloquentSluggable\Services\SlugService;

class SaveCitySegment extends SaveDataFromTM
{
    public function upsertData(array $data): CitySegment
    {
        if (!CitySegment::where('city_name', $data['city'])
            ->where('segment_name', $data['name'])->exists()) {
            $generatedMeta = SeoGen::run('segment', $data['name'], $data['city'], '', '', $data['name']);
            $segment = CitySegment::create(
                [
                    'segment_name' => $data['name'] ?? '',
                    'city_name' => $data['city'] ?? '',
                    'seo_title' => $generatedMeta['data']['title'] ?? '',
                    'seo_keywords' => $generatedMeta['keywords'] ?? '',
                    'seo_description' => $generatedMeta['data']['meta_description'] ?? '',
                    'seo_content' => $generatedMeta['data']['content'] ?? '',
                ]
            );
        } else {
            $segment = CitySegment::where('city_name', $data['city'])
                ->where('segment_name', $data['name'])->first();
        }
        return $segment;
    }
}
