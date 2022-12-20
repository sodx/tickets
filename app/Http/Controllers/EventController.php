<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAttractionRequest;
use App\Http\Requests\UpdateAttractionRequest;
use App\Models\Attraction;
use App\Models\Event;
use Illuminate\Support\Str;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index($location = '', $type = '', $date = '', $date_to = '')
    {
        if ($date === '') {
            $events = Event::where('start_date', '>=', date('Y-m-d'))->get()->sortBy('start_date');
        } elseif ($date !== '' && $date_to === '') {
            $events = Event::where('start_date', date('Y-m-d', strtotime($date)))->get()->sortBy('start_date');
        } elseif ($date !== '' && $date_to !== '') {
            $events = Event::whereBetween('start_date', [
                date('Y-m-d', strtotime($date)),
                date('Y-m-d', strtotime($date_to))
            ])->get()->sortBy('start_date');
        }


        // filter upcoming events and sort by date
        if ($location !== '' && $type !== '') {
            if ($type === 'city') {
                $events = $events->filter(function ($event) use ($location) {
                    return $event->city() === $location;
                });
            } elseif ($type === 'state') {
                $events = $events->filter(function ($event) use ($location) {
                    return $event->state() === $location;
                });
            } elseif ($type === 'country') {
                $events = $events->filter(function ($event) use ($location) {
                    return $event->country() === $location;
                });
            }
        }


        $tourGroup = $this->groupTourEvents($events);
        return view('home', [
            'events' => $tourGroup['single'],
            'tours' => $tourGroup['tour']
        ]);
    }


    public function favorites()
    {
        $favorites = request()->cookie('favorites');
        $favorites = json_decode($favorites, true);
        $events = $favorites ? Event::whereIn('event_id', $favorites)->get() : [];
        return view('home', [
            'events' => $events,
            'tours' => []
        ]);
    }

    public function tours($slug)
    {
        $events = Event::where('name', 'like', '%' . $slug . '%')->get();
        // get upcoming events and sort by date
        $events = $events->filter(function ($event) {
            return $event->start_date >= date('Y-m-d');
        })->sortBy('start_date');

        return view('tour', [
            'events' => $events,
        ]);
    }

    public function unslugify($slug)
    {
        return str_replace('-', ' ', $slug);
    }

    private function groupTourEvents($events)
    {
        $groups = $events->groupBy('tour_id');
        $tourGroup = [
            'single' => [],
            'tour' => []
        ];
        foreach ($groups as $group) {
            if (count($group) > 1) {
                $tourGroup['tour'][] = $group;
            } else {
                $tourGroup['single'][] = $group[0];
            }
        }


        return $tourGroup;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAttractionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAttractionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Attraction  $attraction
     * @return \Illuminate\Http\Response
     */
    public function show(Attraction $attraction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Attraction  $attraction
     * @return \Illuminate\Http\Response
     */
    public function edit(Attraction $attraction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAttractionRequest  $request
     * @param  \App\Models\Attraction  $attraction
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateattractionRequest $request, Attraction $attraction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Attraction  $attraction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attraction $attraction)
    {
        //
    }
}
