<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventSitemapController extends Controller
{
    public function index()
    {
        $alphas = range('a', 'z');

        return response()->view('sitemap.events.index', [
            'alphas' => $alphas,
        ])->header('Content-Type', 'text/xml');
    }

    public function show($letter)
    {
        $events = Event::where('name', 'LIKE', "$letter%")
            ->where('start_date', '>=', date('Y-m-d'))->get();

        return response()->view('sitemap.events.show', [
            'events' => $events,
        ])->header('Content-Type', 'text/xml');
    }
}
