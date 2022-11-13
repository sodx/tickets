<?php

namespace App\Actions;

use App\Models\Segment;
use Cviebrock\EloquentSluggable\Services\SlugService;

class SaveSegment extends SaveDataFromTM
{
    public function upsertData(array $data): Segment
    {
        if (!Segment::where('name', $data['name'])->exists()) {
            $segment = Segment::updateOrCreate(
                ['name' => $data['name']],
                [
                    'name' => $data['name'] ?? '',
                    'slug' => SlugService::createSlug(Segment::class, 'slug', $data['name']),
                ]
            );
        } else {
            $segment = Segment::where('name', $data['name'])->first();
        }
        return $segment;
    }

}
