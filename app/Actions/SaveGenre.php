<?php

namespace App\Actions;

use App\Models\Genre;
use Cviebrock\EloquentSluggable\Services\SlugService;

class SaveGenre extends SaveDataFromTM
{
    public function upsertData(array $data): Genre
    {
        if (!Genre::where('name', $data['name'])->exists()) {
            $genre = Genre::updateOrCreate(
                ['name' => $data['name']],
                [
                    'name' => $data['name'] ?? '',
                    'slug' => SlugService::createSlug(Genre::class, 'slug', $data['name']),
                ]
            );
        } else {
            $genre = Genre::where('name', $data['name'])->first();
        }
        return $genre;
    }

}
