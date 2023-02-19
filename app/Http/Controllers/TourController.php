<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTourRequest;
use App\Http\Requests\UpdateTourRequest;
use App\Models\Event;
use App\Models\Tour;

class TourController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Get tours where more than 1 event. With join clause from events table. Join Event object to output.

        $tours = Event::where('start_date', '>=', date('Y-m-d'))
            ->whereNotNull('tour_id')
            ->orderBy('start_date', 'asc')
            //where more than 1 event
            ->get();
        $tours = $tours->groupBy('tour_id');

        $tourGroup = [];
        // group by tour id and count more than 1
        foreach ($tours as $tour) {
            if (count($tour) > 1) {
                $tourGroup[] = $tour;
            }
        };

        return view('tours', ['tours' => $tourGroup]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTourRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTourRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tour  $tour
     * @return \Illuminate\Http\Response
     */
    public function show(Tour $tour)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tour  $tour
     * @return \Illuminate\Http\Response
     */
    public function edit(Tour $tour)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTourRequest  $request
     * @param  \App\Models\Tour  $tour
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTourRequest $request, Tour $tour)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tour  $tour
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tour $tour)
    {
        //
    }
}
