<?php

use App\Actions\GenerateEventSchema;
use App\Actions\GetMenuItems;
use App\Actions\SeoGen\SeoGen;
use App\Actions\Serpstat\SerpstatClient;
use App\Http\Controllers\AttractionController;
use App\Http\Controllers\AttractionsSitemapController;
use App\Http\Controllers\EventCitySitemapController;
use App\Http\Controllers\EventSitemapController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\ToursSitemapController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VenuesSitemapController;
use App\Models\Page;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TicketMasterController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TourController;
use App\Actions\GetYoutubeVideosByURL;
use App\Actions\Unslugify;
use App\Actions\Slugify;
use App\Models\Post;
use Spatie\Menu\Laravel\Menu;
use App\Actions\GetActiveCity;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * =====================
 * Events Home
 * =====================
 */
Route::get('/', function () {
    if (Cache::has('home')) {
        return Cache::get('home');
    } else {
        // GET request params from current URL
        $requestParams = request()->query();
        // If there are any params, redirect to the URL without params
        SEOMeta::setTitle('All tickets for Music Concerts and Sport Events | ' . setting('site.title'));
        SEOMeta::setDescription('Get information about upcoming events in your city! check ' . setting('site.title'));
        if (count($requestParams) > 0) {
            return redirect()->route('home');
        }

        $activeCity = new getActiveCity();
        $activeCity = $activeCity->handle();
         if ($activeCity['user_location_type'] === 'city' && $activeCity['user_location'] !== 'All Cities') {
            return redirect()->route('city', ['location' => slugify::run($activeCity['user_location'])]);
        }
        $eventController = new EventController();
        $cachedData = $eventController->index()->render();
        Cache::put('home', $cachedData);
        return $cachedData;
    }
})->name('home');


Route::get('/city/{location}', function ($location) {
    if (Cache::has('city_'.$location)) {
        return Cache::get('city_'.$location);
    } else {
        $unslugify = new Unslugify();
        $location = $unslugify->handle($location);
        $eventController = new EventController();
        SEOMeta::setTitle('Music Concerts and Sport Events in ' . $location . ' | Buy tickets on ' . setting('site.title'));
        SEOMeta::setDescription('Get information about upcoming events in ' . $location . '! check ' . setting('site.title'));
        if ($location === 'All Cities') {
            return redirect()->route('home');
        }
        $cachedData = $eventController->index($location, 'city')->render();
        Cache::put('city_'.$location, $cachedData);
        return $cachedData;
    }
})->name('city');

Route::get('/city/{location}/events', function ($location) {
    if (Cache::has('city_'.$location.'_events')) {
        return Cache::get('city_'.$location.'_events');
    } else {
        $unslugify = new Unslugify();
        $location = $unslugify->handle($location);
        $eventController = new EventController();
        SEOMeta::setTitle('Music Concerts and Sport Events in ' . $location . ' | Buy tickets on ' . setting('site.title'));
        SEOMeta::setDescription('Get information about upcoming events in ' . $location . '! check ' . setting('site.title'));

        if ($location === 'All Cities') {
            return $eventController->indexEvents();
        }
        $cachedData = $eventController->indexEvents($location, 'city')->render();
        Cache::put('city_'.$location.'_events', $cachedData);
        return $cachedData;
    }
})->name('events');


/**
 * =====================
 * Events Listings with Filters
 * =====================
 */
Route::get('/city/{location}/segment/{slug}', function ($location, $slug) {
    SEOMeta::setTitle($slug . ' Events in '. $location .' | Buy tickets on ' . setting('site.title'));
    SEOMeta::setDescription('Get all '. $slug .' upcoming events in '. $location .'! check ' . setting('site.title'));

    $term = App\Models\Segment::where('slug', '=', $slug)->firstOrFail();
    return view('term', compact('term', 'location'));
})->name('segment');

Route::get('/city/{location}/genre/{slug}', function ($location, $slug) {
    $unslugify = new Unslugify();
    $location = $unslugify->handle($location);
    $eventController = new EventController();
    SEOMeta::setTitle($slug . ' Events in '. $location .' | Buy tickets on ' . setting('site.title'));
    SEOMeta::setDescription('Get all '. $slug .' upcoming events in '. $location .'! check ' . setting('site.title'));

    return $eventController->indexEvents($location, 'city', '', '', $slug);
})->name('genre');

Route::get('/city/{location}/segment/{slug}', function ($location, $slug) {
    $unslugify = new Unslugify();
    $location = $unslugify->handle($location);
    $eventController = new EventController();

    SEOMeta::setTitle($slug . ' Events in '. $location .' | Buy tickets on ' . setting('site.title'));
    SEOMeta::setDescription('Get all '. $slug .' upcoming events in '. $location .'! check ' . setting('site.title'));

    return $eventController->indexEvents($location, 'city', '', '', '', $slug);
})->name('segment');

Route::get('/city/{location}/segment/{slug}/genre/{genre}', function ($location, $slug, $genre) {
    $unslugify = new Unslugify();
    $location = $unslugify->handle($location);
    $eventController = new EventController();
    SEOMeta::setTitle($slug . ' Events in '. $location .' | Buy tickets on ' . setting('site.title'));
    SEOMeta::setDescription('Get all '. $slug .' upcoming events in '. $location .'! check ' . setting('site.title'));

    return $eventController->indexEvents($location, 'city', '', '', $genre, $slug);
})->name('segment_genre');

Route::get('/city/{location}/segment/{slug}/date/{date}', function ($location, $slug, $date) {
    $unslugify = new Unslugify();
    $location = $unslugify->handle($location);
    $eventController = new EventController();
    return $eventController->indexEvents($location, 'city', $date, '', '', $slug);
})->name('segment_date');

Route::get('/city/{location}/date/{date}', function ($location, $date) {
    $unslugify = new Unslugify();
    $location = $unslugify->handle($location);
    $eventController = new EventController();
    return $eventController->indexEvents($location, 'city', $date);
})->name('date');

Route::get('/city/{location}/date/{date}/date_to/{date_to}', function ($location, $date, $date_to) {
    $unslugify = new Unslugify();
    $location = $unslugify->handle($location);
    $eventController = new EventController();
    return $eventController->indexEvents($location, 'city', $date, $date_to);
})->name('date_to');


/**
 * =====================
 * Single Event
 * =====================
 */
Route::get('/city/{location}/segment/{segment}/event/{slug}', function ($location, $segment, $slug) {
    if (Cache::has('events_'.$slug)) {
        return Cache::get('events_'.$slug);
    } else {
        $eventController = new EventController();

        $cachedData = $eventController->show($slug, $location, $segment);
        if($cachedData instanceof \Illuminate\View\View) {
            $cachedData = $cachedData->render();
            Cache::put('events_'.$slug, $cachedData);
        }
        return $cachedData;
    }
})->name('event');


/**
 * =====================
 * Single Venue
 * =====================
 */
Route::get('venue/{slug}', function ($slug) {
    $venue = App\Models\Venue::where('slug', '=', $slug)->firstOrFail();
    SEOMeta::setTitle('Events in '. $venue->name . ' | ' . setting('site.title'));
    SEOMeta::setDescription('All upcoming events in '. $venue->name . '! check ' . setting('site.title'));
    return view('venue', compact('venue'));
})->name('venue');


/**
 * =====================
 * Single Atraction
 * =====================
 */
Route::get('attractions', function () {
    $attractionController = new AttractionController();
    return $attractionController->index();
})->name('attractions');
Route::get('attractions/{slug}', function ($slug) {
    $attraction = App\Models\Attraction::where('slug', '=', $slug)->firstOrFail();
    SEOMeta::setTitle($attraction->name . ' - Events Get Tickets | ' . setting('site.title'));
    SEOMeta::setDescription('Get upcoming events with '. $attraction->name . '! check ' . setting('site.title'));

    return view('attraction', compact('attraction'));
})->name('attraction');


/**
 * =====================
 * Single Tour with Events
 * =====================
 */
Route::get('tours', function () {
    $tours = new TourController();
    SEOMeta::setTitle('Tours | ' . setting('site.title'));
    SEOMeta::setDescription('All upcoming tours! check ' . setting('site.title'));
    return $tours->index();
})->name('tours');

Route::get('tours/{slug}', function ($slug) {
    $tour = App\Models\Tour::where('slug', '=', $slug)->firstOrFail();
    SEOMeta::setTitle($tour->name . ' - Events | ' . setting('site.title'));
    SEOMeta::setDescription('Get all ' . $tour->name . ' info on - ' . setting('site.title'));
    return view('tour', compact('tour'));
})->name('tour');


Route::get('favorites', function () {
    $eventController = new EventController();
    return $eventController->favorites();
})->name('favorites');


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();

    Route::get('perform', function () {
        $tt = new TicketMasterController();
        return $tt->index();
    })->middleware('admin.user');
});


Route::get('post/{slug}', function ($slug) {
    $post = Post::where('slug', '=', $slug)->firstOrFail();
    return view('post', compact('post'));
})->name('post');

Route::get('page/{slug}', function ($slug) {
    $page = Page::where('slug', '=', $slug)->firstOrFail();
    SEOMeta::setTitle($page->title . ' | ' . setting('site.title'));
    SEOMeta::setDescription($page->meta_description);
    return view('post', ['post' => $page]);
})->name('page');

Route::controller(SearchController::class)->group(function () {
    Route::get('autocomplete', 'autocomplete')->name('autocomplete');
    Route::get('search/{term}', function ($term) {
        $searchController = new SearchController();
        SEOMeta::setTitle('Search results for ' . $term . ' | ' . setting('site.title'));
        SEOMeta::setDescription('Search results for ' . $term . ' | ' . setting('site.title'));
        return $searchController->index($term);
    })->name('search');
});

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap.index');
Route::get('/sitemap/events.xml', [EventSitemapController::class, 'index'])->name('sitemap.events.index');
Route::get('/sitemap/events/{letter}.xml', [EventSitemapController::class, 'show'])->name('sitemap.events.show');
Route::get('/sitemap/events-cities.xml', [EventCitySitemapController::class, 'index'])->name('sitemap.eventsCity.index');
Route::get('/sitemap/events-cities/{city}.xml', [EventCitySitemapController::class, 'show'])->name('sitemap.eventsCity.show');
Route::get('/sitemap/venues.xml', [VenuesSitemapController::class, 'index'])->name('sitemap.venues.index');
Route::get('/sitemap/attractions.xml', [AttractionsSitemapController::class, 'index'])->name('sitemap.attractions.index');
