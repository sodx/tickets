<?php

namespace App\Actions;

use App\Models\Subgenre;
use Cviebrock\EloquentSluggable\Services\SlugService;

class SaveSubgenre extends SaveDataFromTM
{
    public function upsertData(array $data): Subgenre
    {
        if (!Subgenre::where('name', $data['name'])->exists()) {
            $subgenre = Subgenre::updateOrCreate(
                ['name' => $data['name']],
                [
                    'name' => $data['name'] ?? '',
                    'slug' => SlugService::createSlug(Subgenre::class, 'slug', $data['name']),
                ]
            );
        } else {
            $subgenre = Subgenre::where('name', $data['name'])->first();
        }
        return $subgenre;
    }
}
