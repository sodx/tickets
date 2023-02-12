<?php

namespace App\Http\Controllers;

use App\Models\Attraction;
use App\Models\Event;
use App\Models\Venue;
use Illuminate\Http\Request;

class AttractionsSitemapController extends Controller
{
    public function index()
    {
        $attractions = Attraction::whereHas('events', function ($query) {
            $query->where('start_date', '>=', date('Y-m-d'));
        })->get();
        ray($attractions);
        $attractions->firstEvent = $attractions->first()->events()->where('start_date', '>=', date('Y-m-d'))->first();

        return response()->view('sitemap.attractions.index', [
            'attractions' => $attractions,
        ])->header('Content-Type', 'text/xml');
    }
}
