<?php

namespace App\Actions;

use App\Actions\SeoGen\SeoGen;
use App\Models\Genre;
use Cviebrock\EloquentSluggable\Services\SlugService;

class SaveGenre extends SaveDataFromTM
{
    public function upsertData(array $data): Genre
    {
        if (!Genre::where('name', $data['name'])->exists()) {
            $generatedMeta = SeoGen::run('segment', $data['name'], '', '', '');
            $genre = Genre::updateOrCreate(
                ['name' => $data['name']],
                [
                    'name' => $data['name'] ?? '',
                    'slug' => SlugService::createSlug(Genre::class, 'slug', $data['name']),
                    'seo_title' => $generatedMeta['data']['title'] ?? '',
                    'seo_keywords' => $generatedMeta['keywords'] ?? '',
                    'seo_description' => $generatedMeta['data']['meta_description'] ?? '',
                    'seo_content' => $generatedMeta['data']['content'] ?? '',
                ]
            );
        } else {
            $genre = Genre::where('name', $data['name'])->first();
        }
        return $genre;
    }
}
