<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Post;
use Illuminate\Http\Request;

class SitemapController extends Controller
{
    public function index()
    {
        $event = Event::orderBy('updated_at', 'desc')->first();

        return response()->view('sitemap.index', [
            'event' => $event,
        ])->header('Content-Type', 'text/xml');
    }
}
