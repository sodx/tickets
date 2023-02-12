<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Venue;
use Illuminate\Http\Request;

class VenuesSitemapController extends Controller
{
    public function index()
    {
        $venues = Venue::whereHas('events', function ($query) {
            $query->where('start_date', '>=', date('Y-m-d'));
        })->get();
        $venues->firstEvent = $venues->first()->events()->where('start_date', '>=', date('Y-m-d'))->first();

        return response()->view('sitemap.venues.index', [
            'venues' => $venues,
        ])->header('Content-Type', 'text/xml');
    }
}
