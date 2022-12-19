<?php

namespace App\Actions;

use App\Models\Tour;
use Cviebrock\EloquentSluggable\Services\SlugService;

class SaveTour extends SaveDataFromTM
{
    public function upsertData(array $data): Tour
    {
        if (!Tour::where('name', $data['name'])->exists()) {
            $genre = Tour::updateOrCreate(
                ['name' => $data['name']],
                [
                    'name' => $data['name'] ?? '',
                    'slug' => SlugService::createSlug(Tour::class, 'slug', $data['name']),
                ]
            );
        } else {
            $genre = Tour::where('name', $data['name'])->first();
        }
        return $genre;
    }
}
