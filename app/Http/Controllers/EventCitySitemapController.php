<?php

namespace App\Http\Controllers;

use App\Actions\GetCities;
use App\Models\Segment;
use Illuminate\Http\Request;

class EventCitySitemapController extends Controller
{
    public function index()
    {
        $states = GetCities::run();

        return response()->view('sitemap.eventsCity.index', [
            'states' => $states,
        ])->header('Content-Type', 'text/xml');
    }

    public function show($city)
    {
        $segments = Segment::all();
        $segments = $segments->filter(function ($segment) use ($city) {
            $segment->events = $segment->events()->whereHas('venue', function ($query) use ($city) {
                $query->where('city', $city);
            })->get();
            return $segment->events->count() > 0;
        });

        return response()->view('sitemap.eventsCity.show', [
            'city' => $city,
            'segments' => $segments ?? [],
        ])->header('Content-Type', 'text/xml');
    }
}
