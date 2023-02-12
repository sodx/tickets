<?php

namespace App\Actions;

use App\Models\Event;
use App\Models\Genre;
use App\Models\Segment;
use Lorisleiva\Actions\Concerns\AsAction;

class GetMenuItems
{
    use AsAction;

    public $city;

    public $segments;
    public $genres;

    public function handle()
    {
        $this->city = GetActiveCity::run();
        $this->segments = $this->getActiveSegments();
        $this->genres = $this->getActiveGenres();
        return $this->groupGenresBySegments();
    }

    private function getActiveSegments()
    {
        $city = $this->city['user_location'];
        $genres = Segment::all();
        return $genres->filter(function ($genre) use ($city) {
            return $genre->eventsInCity($city)->count() > 0;
        });
    }

    private function getActiveGenres()
    {
        $city = $this->city['user_location'];
        $genres = Genre::all();
        return $genres->filter(function ($genre) use ($city) {
            return $genre->eventsInCity($city)->count() > 0;
        });
    }

    private function groupGenresBySegments(): array
    {
        $segments = $this->segments;
        $genres = $this->genres;
        $output = [];
        foreach ($segments as $segmentKey => $segment) {
            $output[$segmentKey] = [];
            $output[$segmentKey]['genres'] = [];
            $output[$segmentKey]['slug'] = $segment->slug;
            $output[$segmentKey]['name'] = $segment->name;
            foreach ($genres as $genre) {
                if ($this->isGenreInSegment($genre, $segment)) {
                    $output[$segmentKey]['genres'][] = $genre;
                }
            }
        }
        return $output;
    }

    private function isGenreInSegment($genre, $segment)
    {
        return Event::where('genre_id', $genre->id)
                ->where('segment_id', $segment->id)
                ->where('start_date', '>=', date('Y-m-d'))
                ->count() > 0;
    }
}
