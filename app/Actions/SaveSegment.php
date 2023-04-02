<?php

namespace App\Actions;

use App\Actions\SeoGen\SeoGen;
use App\Models\Segment;
use Cviebrock\EloquentSluggable\Services\SlugService;

class SaveSegment extends SaveDataFromTM
{
    public function upsertData(array $data): Segment
    {
        if (!Segment::where('name', $data['name'])->exists()) {
            $generatedMeta = SeoGen::run('segment', $data['name'], '', '', '');
            $segment = Segment::updateOrCreate(
                ['name' => $data['name']],
                [
                    'name' => $data['name'] ?? '',
                    'slug' => SlugService::createSlug(Segment::class, 'slug', $data['name']),
                    'seo_title' => $generatedMeta['data']['title'] ?? '',
                    'seo_keywords' => $generatedMeta['keywords'] ?? '',
                    'seo_description' => $generatedMeta['data']['meta_description'] ?? '',
                    'seo_content' => $generatedMeta['data']['content'] ?? '',
                ]
            );
        } else {
            $segment = Segment::where('name', $data['name'])->first();
        }
        return $segment;
    }
}
