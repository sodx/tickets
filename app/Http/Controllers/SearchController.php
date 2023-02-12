<?php

namespace App\Http\Controllers;

use App\Actions\GetActiveCity;
use App\Actions\Slugify;
use App\Models\Attraction;
use App\Models\Event;
use App\Models\Tour;
use Illuminate\Http\Request;
use App\Models\User;

class SearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('searchDemo');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function autocomplete(Request $request)
    {
        $city = GetActiveCity::run();
        $events = Event::select()
            ->where('name', 'LIKE', '%'. $request->get('search'). '%')
            ->where('start_date', '>=', date('Y-m-d'))
            ->get()->sortBy('start_date');

        $eventsInUserCity = $events->filter(function ($event) use ($city) {
            return $event->city() === $city['user_location'];
        });

        $attractions = Attraction::select()
            ->where('name', 'LIKE', '%'. $request->get('search'). '%')
            ->get();

        $tour = Tour::select()
            ->where('name', 'LIKE', '%'. $request->get('search'). '%')
            ->get();

        $data = [
            'eventsInUserCity' => [
                'eventsInUserCity' => $this->processEventsInUserCity($eventsInUserCity)
            ],
            'all_events' => [
                'all_events' => count($events)
            ],
            'attractions' => [
                'attractions' => $this->processAttractions($attractions)
            ],
            'tours' => [
                'tours' => $this->processTour($tour)
            ]
        ];

        return response()->json($data);
    }

    private function processEventsInUserCity($eventsInUserCity)
    {
        return $eventsInUserCity->map(function ($event) {
            return [
                'name' => $event->name,
                'url' => route('event', [
                    'location' => Slugify::run($event->city()),
                    'segment' => $event->segment->slug,
                    'slug' => $event->slug
                ]),
                'image' => $event->thumbnail,
                'date' => $event->start_date,
                'type' => 'event'
            ];
        });
    }

    private function processAttractions($attractions)
    {
        return $attractions->map(function ($attraction) {
            return [
                'name' => $attraction->name,
                'url' => route('attraction', $attraction->slug),
                'image' => $attraction->thumbnail,
                'type' => 'attraction'
            ];
        });
    }

    private function processTour($tour)
    {
        return $tour->map(function ($tour) {
            return [
                'name' => $tour->name,
                'url' => route('tour', $tour->slug),
                'image' => $tour->thumbnail,
                'type' => 'tour'
            ];
        });
    }
}
