<?php

namespace App\Http\Controllers;

use App\Actions\ArchiveSeoMeta;
use App\Http\Requests\StoreAttractionRequest;
use App\Http\Requests\UpdateAttractionRequest;
use App\Models\Attraction;
use App\Models\Event;
use Artesaos\SEOTools\Facades\SEOMeta;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Support\Str;
use Kyslik\ColumnSortable\Sortable;

class EventController extends Controller
{
    public function queryEvents($location = '', $type = '', $date = '', $date_to = '', $sort = '', $genre = '', $segment = '')
    {
        if ($date === '') {
            $events = Event::where('start_date', '>=', date('Y-m-d'))->get()->sortBy($sort);
        } elseif ($date !== '' && $date_to === '') {
            $events = Event::where('start_date', '>=', date('Y-m-d', strtotime($date)))->get()->sortBy($sort);
        } elseif ($date !== '' && $date_to !== '') {
            $events = Event::whereBetween('start_date', [
                date('Y-m-d', strtotime($date)),
                date('Y-m-d', strtotime($date_to))
            ])->get()->sortBy('start_date');
        }
        // filter upcoming events and sort by date
        if ($location !== '' && $type !== '') {
            if ($type === 'city' && $location !== '' && $location !== 'All Cities') {
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

        if ($segment !== '' && $segment !== 'All Segments') {
            $events = $events->filter(function ($event) use ($segment) {
                if(isset($event->segment['slug'])) {
                    return $event->segment['slug'] === $segment;
                }
            });
        }

        if ($genre !== '' && $genre !== 'All Genres' ) {
            $events = $events->filter(function ($event) use ($genre) {
                if(isset($event->genre['slug'])) {
                    return $event->genre['slug'] === $genre;
                }
            });
        }

        return $events;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index($location = '', $type = '', $date = '', $date_to = '')
    {
        $events= $this->queryEvents($location, $type, $date, $date_to);

        $tourGroup = $this->groupTourEvents($events);
        $topViewed = $this->getTopViewed($events);
        $seoMeta = ArchiveSeoMeta::run($location, $type, $date, $date_to);

        SEOMeta::setTitle($seoMeta['title']);
        SEOMeta::setDescription($seoMeta['description']);
        return view('home', [
            'topViewed' => $topViewed,
            'events' => array_slice($tourGroup['single'], 0, 8),
            'tours' => array_slice($tourGroup['tour'], 0, 4),
            'h1' => $seoMeta['h1'],
        ]);
    }

    public function indexEvents($location = '', $type = '', $date = '', $date_to = '', $genre = '', $segment = '')
    {
        $sort = request()->query('sort') ?? 'start_date';
        $perPage = request()->query('per_page') ?? 20;
        $date = request()->query('date') ?? '';
        $date_to = request()->query('date_to') ?? '';
        $events = $this->queryEvents($location, $type, $date, $date_to, $sort, $genre, $segment);

        $events = $events->paginate($perPage);
        $seoMeta = ArchiveSeoMeta::run($location, $type, $date, $date_to, $genre, $segment);

        SEOMeta::setTitle($seoMeta['title']);
        SEOMeta::setDescription($seoMeta['description']);

        return view('archive', [
            'topViewed' => [],
            'events' => $events,
            'tours' => [],
            'links' => $events->links('vendor.pagination.default'),
            'h1' => $seoMeta['h1'],
        ]);
    }


    public function getTopViewed($events)
    {
        $events = $events->sortByDesc('views');
        return $events->take(8);
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

    public function recentlyViewed()
    {
        $recentlyViewed = request()->cookie('recently_viewed');
        $recentlyViewed = json_decode($recentlyViewed, true);
        $events = $recentlyViewed ? Event::whereIn('event_id', $recentlyViewed)->get() : [];
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
