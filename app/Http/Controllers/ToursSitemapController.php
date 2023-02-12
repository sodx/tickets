<?php

namespace App\Http\Controllers;

use App\Models\Attraction;
use App\Models\Event;
use App\Models\Tour;
use App\Models\Venue;
use Illuminate\Http\Request;

class ToursSitemapController extends Controller
{
    public function index()
    {
        $tours = Tour::whereHas('events', function ($query) {
            $query->where('start_date', '>=', date('Y-m-d'));
        })->get();

        $tours->firstEvent = $tours->first()->events()->where('start_date', '>=', date('Y-m-d'))->first();

        return response()->view('sitemap.tours.index', [
            'tours' => $tours,
        ])->header('Content-Type', 'text/xml');
    }
}
