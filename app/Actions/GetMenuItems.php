<?php

namespace App\Actions;

use App\Models\Event;
use App\Models\Genre;
use App\Models\Segment;
use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\Menu\Laravel\Menu;

class GetMenuItems
{
    use AsAction;

    public $city;

    public $segments;
    public $genres;

    public function handle($activeCity = null)
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
        if ($city === 'All Cities') {
            return $genres;
        }
        return $genres->filter(function ($genre) use ($city) {
            return $genre->eventsInCity($city)->count() > 0;
        });
    }

    private function getActiveGenres()
    {
        $city = $this->city['user_location'];
        $genres = Genre::all();
        if ($city === 'All Cities') {
            return $genres;
        }
        return $genres->filter(function ($genre) use ($city) {
            return $genre->eventsInCity($city)->count() > 0;
        });
    }

    public function groupGenresBySegments(): array
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

    public function isGenreInSegment($genre, $segment)
    {
        return Event::where('genre_id', $genre->id)
                ->where('segment_id', $segment->id)
                ->where('start_date', '>=', date('Y-m-d'))
                ->count() > 0;
    }


    public function getSubMenu($menuItems, $key, $activeCity)
    {
        if (!array_key_exists($key, $menuItems)) {
            return;
        }
        $menu = Menu::new();
        $menu->addClass('submenu');
        foreach ($menuItems[$key]['genres'] as $genre) {
            if ($genre->slug === 'undefined') {
                continue;
            }
            $menu->route('genre', $genre->name, [
                'location' => Slugify::run($activeCity['user_location']),
                'slug' => $genre->slug,
            ]);
        }
        return $menu;
    }

    public function getSubMenuHeader($menuItems, $key, $activeCity)
    {
        if (!array_key_exists($key, $menuItems)) {
            return '';
        }
        return '<a class="has-submenu"
        href="/city/' . Slugify::run($activeCity['user_location']) . '/segment/'.$menuItems[$key]['slug'].'">'
            .$menuItems[$key]['name'] .'<span class="material-symbols-outlined">arrow_drop_down</span></a>';
    }
}
