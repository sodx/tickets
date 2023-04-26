<?php

namespace App\Http\Controllers;

use App\Actions\ArchiveSeoMeta;
use App\Actions\GenerateEventSchema;
use App\Actions\GenerateSeoMeta;
use App\Actions\Slugify;
use App\Actions\Unslugify;
use App\Http\Requests\StoreAttractionRequest;
use App\Http\Requests\UpdateAttractionRequest;
use App\Models\Attraction;
use App\Models\Event;
use App\Models\Venue;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use Kyslik\ColumnSortable\Sortable;

class EventController extends Controller
{
    public function queryEvents($location = '', $type = '', $date = '', $dateTo = '', $sort = '', $genre = '', $segment = '')
    {
        if ($date === '') {
//            $events = Event::all()->sortBy($sort);
            $events = Event::where('start_date', '>=', date('Y-m-d'))->where('status', '=', 'onsale')->whereHas('segment')->get()->sortBy($sort);
        } elseif ($date !== '' && $dateTo === '') {
            $events = Event::where('start_date', '>=', date('Y-m-d', strtotime($date)))->where('status', '=', 'onsale')->whereHas('segment')->get()->sortBy($sort);
        } elseif ($date !== '' && $dateTo !== '') {
            $events = Event::whereBetween('start_date', [
                date('Y-m-d', strtotime($date)),
                date('Y-m-d', strtotime($dateTo))
            ])->where('status', '=', 'onsale')->whereHas('segment')->get()->sortBy('start_date');
        }

        // filter upcoming events and sort by date
        if ($location !== '' && $type !== '') {
            if ($type === 'city' && $location !== '' && $location !== 'All Cities') {
                $events = $events->filter(function ($event) use ($location) {
                    return strtolower(trim($event->city())) === strtolower(trim($location));
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
                if (isset($event->segment['slug'])) {
                    return $event->segment['slug'] === $segment;
                }
            });
        }

        if ($genre !== '' && $genre !== 'All Genres') {
            $events = $events->filter(function ($event) use ($genre) {
                if (isset($event->genre['slug'])) {
                    return $event->genre['slug'] === $genre;
                }
            });
        }

        return [
            'events' => $events,
            'featuredEvent' => $events->sortByDesc('views')->first()
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index($location = '', $type = '', $date = '', $dateTo = '')
    {
        $eventsQuery = $this->queryEvents($location, $type, $date, $dateTo);
        $events = $eventsQuery['events'];
        $seoMeta = ArchiveSeoMeta::run($location, $type, $date, $dateTo);
        SEOMeta::setTitle($seoMeta['title']);
        SEOMeta::setDescription($seoMeta['description']);

        if (empty($events->toArray())) {
            if (Venue::where('city', '=', $location)->count() > 0) {
                return view('no-events-city', [
                    'h1' => $location,
                    'subheading' => 'There is no upcoming events in ' . $location,
                    'text' => 'Please check back later or try another city.'
                ]);
            }
            abort(404, 'Page not found');
        }
        $tourGroup = $this->groupTourEvents($events);
        $topViewed = $this->getTopViewed($events);


        return view('home', [
            'location' => $location === '' ? 'All Cities' : $location,
            'featuredEvent' => $eventsQuery['featuredEvent'],
            'topViewed' => $topViewed,
            'events' => array_slice($tourGroup['single'], 0, 8),
            'h1' => $seoMeta['h1'],
            'seoText' => $seoMeta['seoText'] ?? '',
        ]);
    }

    public function indexEvents($location = '', $type = '', $date = '', $dateTo = '', $genre = '', $segment = '')
    {
        $sort = request()->query('sort') ?? 'start_date';
        $perPage = request()->query('per_page') ?? 20;
        $date = request()->query('date') ?? '';
        $dateTo = request()->query('date_to') ?? '';
        $eventsQuery = $this->queryEvents($location, $type, $date, $dateTo, $sort, $genre, $segment);
        $events = $eventsQuery['events'];

        $events = $events->paginate($perPage);
        if (empty($events->items())) {
            abort(404, 'Page not found');
        }
        $seoMeta = ArchiveSeoMeta::run($location, $type, $date, $dateTo, $genre, $segment);

        SEOMeta::setTitle($seoMeta['title']);
        SEOMeta::setDescription($seoMeta['description']);
        $eventsArr = $events->toArray();
        $currentPage = $eventsArr['current_page'];

        if($currentPage > 1) {
            // add canonical link
            SEOMeta::setPrev(url()->current() . '?page=' . ($currentPage - 1) . '&per_page=' . $perPage);
            SEOMeta::setNext(url()->current() . '?page=' . ($currentPage + 1) . '&per_page=' . $perPage);
            SEOMeta::setCanonical(url()->current());
            SEOMeta::addMeta('robots', 'noindex, follow');
        } elseif ($date !== '' || $dateTo !== '') {
            SEOMeta::setCanonical(url()->current());
            SEOMeta::addMeta('robots', 'noindex, follow');
        }
        return view('archive', [
            'featuredEvent' => $eventsQuery['featuredEvent'],
            'topViewed' => [],
            'events' => $events,
            'links' => $events->links('vendor.pagination.default'),
            'h1' => $seoMeta['h1'],
            'seoText' => $seoMeta['seoText'] ?? '',
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
        SEOMeta::setTitle('Favorite Events'. ' | '. config('site.title'));
        SEOMeta::setDescription('Your favorite events');

        return view('home', [
            'events' => $events,
            'topViewed' => [],
            'h1' => 'Favorite Events'
        ]);
    }

    public function recentlyViewed()
    {
        $recentlyViewed = request()->cookie('recently_viewed');
        $recentlyViewed = json_decode($recentlyViewed, true);
        $events = $recentlyViewed ? Event::whereIn('event_id', $recentlyViewed)->get() : [];
        return view('home', [
            'events' => $events,
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

    private function getRelatedEvents($event, $quantity = 8, $states = false)
    {
        $events = Event::where('event_id', '!=', $event->event_id)
            ->where('start_date', '>=', date('Y-m-d'))
            ->whereHas('venue', function ($query) use ($event) {
                $query->where('city', '=', $event->venue->city);
            })
            ->whereHas('segment', function ($query) use ($event) {
                $query->where('name', '=', $event->segment->name);
            })
            ->orderBy('clicks')
            ->take($quantity)
            ->get();
        if ($events->count() < $quantity) {
            $eventsInState = Event::where('event_id', '!=', $event->event_id)
                ->where('start_date', '>=', date('Y-m-d'))
                ->whereHas('venue', function ($query) use ($event) {
                    $query->where('state', '=', $event->venue->state);
                })
                ->orderBy('clicks')
                ->take($quantity - $events->count())
                ->get();
            $events = $events->merge($eventsInState);
        }

        return $events;
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
    public function show($slug, $location, $segment)
    {
        $event = Event::where('slug', '=', $slug)
            ->whereHas('venue', function ($query) use ($location) {
                $query->where('city', '=', Unslugify::run($location));
            })
            ->whereHas('segment', function ($query) use ($segment) {
                $query->where('name', '=', Unslugify::run($segment));
            })
            ->first();
        if (!$event) {
            return redirect()->route('segment', ['location' => $location, 'slug' => $segment]);
            //abort(404);
        }
        $seoMeta = GenerateSeoMeta::run($event);
        SEOMeta::setTitle($event->meta_title !== '' ? $event->meta_title : $seoMeta['title']);
        SEOMeta::setDescription($event->meta_description !== '' ? $event->meta_description : $seoMeta['description']);
        SEOMeta::addMeta('article:published_time', $event->created_at->toW3CString(), 'property');
        SEOMeta::addMeta('article:section', $event->category, 'property');

        OpenGraph::setDescription($event->info);
        OpenGraph::setTitle($event->title);
        OpenGraph::setUrl(Request::url());
        OpenGraph::setSiteName('Music Snobbery');
        OpenGraph::addProperty('type', 'article');
        OpenGraph::addProperty('locale', 'en-us');
        OpenGraph::addImage($event->medium_image);
        $schema = GenerateEventSchema::run($event);

        $event->views = $event->views + 1;
        $event->save();

        return view('event', [
            'event' => $event,
            'schema' => $schema['event'].$schema['faq'],
            'relatedEvents' => $relatedEvents = $this->getRelatedEvents($event)
        ]);
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
