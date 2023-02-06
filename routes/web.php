<?php

use App\Actions\GenerateEventSchema;
use App\Http\Controllers\EventCitySitemapController;
use App\Http\Controllers\EventSitemapController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\UserController;
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
    $eventController = new EventController();
    return $eventController->index();
})->name('home');

Route::get('/city/{location}', function ($location) {
    $unslugify = new Unslugify();
    $location = $unslugify->handle($location);
    $eventController = new EventController();
    if ($location === 'All Cities') {
        return $eventController->index();
    }
    return $eventController->index($location, 'city');
})->name('city');

Route::get('/city/{location}/events', function ($location) {
    $unslugify = new Unslugify();
    $location = $unslugify->handle($location);
    $eventController = new EventController();
    if ($location === 'All Cities') {
        return $eventController->indexEvents();
    }
    return $eventController->indexEvents($location, 'city');
})->name('events');


/**
 * =====================
 * Events Listings with Filters
 * =====================
 */
Route::get('/city/{location}/segment/{slug}', function ($location, $slug) {
    $term = App\Models\Segment::where('slug', '=', $slug)->firstOrFail();
    return view('term', compact('term', 'location'));
})->name('segment');

Route::get('/city/{location}/genre/{slug}', function ($location, $slug) {
    $term = App\Models\Genre::where('slug', '=', $slug)->firstOrFail();
    return view('term', compact('term', 'location'));
})->name('genre');

Route::get('/city/{location}/genre/{slug}', function ($location, $slug) {
    $unslugify = new Unslugify();
    $location = $unslugify->handle($location);
    $eventController = new EventController();
    return $eventController->indexEvents($location, 'city', '', '', $slug);
})->name('genre');

Route::get('/city/{location}/segment/{slug}', function ($location, $slug) {
    $unslugify = new Unslugify();
    $location = $unslugify->handle($location);
    $eventController = new EventController();
    return $eventController->indexEvents($location, 'city', '', '', '', $slug);
})->name('segment');

Route::get('/city/{location}/segment/{slug}/genre/{genre}', function ($location, $slug, $genre) {
    $unslugify = new Unslugify();
    $location = $unslugify->handle($location);
    $eventController = new EventController();
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
})->name('date');


/**
 * =====================
 * Single Event
 * =====================
 */
Route::get('/city/{location}/segment/{segment}/event/{slug}', function ($location, $segment, $slug) {
    $event = App\Models\Event::where('slug', '=', $slug)->firstOrFail();

    SEOMeta::setTitle($event->meta_title);
    SEOMeta::setDescription($event->meta_description);
    SEOMeta::addMeta('article:published_time', $event->created_at->toW3CString(), 'property');
    SEOMeta::addMeta('article:section', $event->category, 'property');

    OpenGraph::setDescription($event->info);
    OpenGraph::setTitle($event->title);
    OpenGraph::setUrl(Request::url());
    OpenGraph::setSiteName('Live Concerts');
    OpenGraph::addProperty('type', 'article');
    OpenGraph::addProperty('locale', 'en-us');
    OpenGraph::addImage($event->medium_image);

    $event->views = $event->views + 1;
    $event->save();
    $schema = GenerateEventSchema::run($event);

    return view('event', [
        'event' => $event,
        'schema' => $schema['event'].$schema['faq'],
     //   'breadcrumbsJson' => $breadcrumbsJson,
    ]);
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
Route::get('attraction/{slug}', function ($slug) {
    $attraction = App\Models\Attraction::where('slug', '=', $slug)->firstOrFail();
    SEOMeta::setTitle($attraction->name . ' - Events | ' . setting('site.title'));
    SEOMeta::setDescription('All upcoming events with '. $attraction->name . '! check ' . setting('site.title'));

    return view('attraction', compact('attraction'));
})->name('attraction');


/**
 * =====================
 * Single Tour with Events
 * =====================
 */
Route::get('tour/{slug}', function ($slug) {
    $tour = App\Models\Tour::where('slug', '=', $slug)->firstOrFail();
    return view('tour', compact('tour'));
})->name('tour');


Route::get('favorites', function () {
    $eventController = new EventController();
    return $eventController->favorites();
})->name('favorites');


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Route::get('perform', function () {
    $tt = new TicketMasterController();
    $tt->index();
});

Menu::macro('main', function () {
    $activeCity = GetActiveCity::run();
    $concertsUrl = '<a class="has-submenu" href="/city/' . Slugify::run($activeCity['user_location']) . '/segment/music">Concerts</a>';
    $sportsUrl = '<a class="has-submenu" href="/city/' . Slugify::run($activeCity['user_location']) . '/segment/sports">Sports</a>';
    return Menu::new()
        ->route('city', 'Home', [
            'location' => Slugify::run($activeCity['user_location']),
        ])
        ->submenu(
            $concertsUrl,
            Menu::new()
                ->addClass('submenu')
                ->route('genre', 'Country', [
                    'location' => Slugify::run($activeCity['user_location']),
                    'slug' => 'country'
                ])
                ->route('genre', 'Rock', [
                    'location' => Slugify::run($activeCity['user_location']),
                    'slug' => 'rock'
                ])
                ->route('genre', 'Pop', [
                    'location' => Slugify::run($activeCity['user_location']),
                    'slug' => 'pop'
                ])
                ->route('genre', 'R&B', [
                    'location' => Slugify::run($activeCity['user_location']),
                    'slug' => 'r-b'
                ])
                ->route('genre', 'Hip Hop', [
                    'location' => Slugify::run($activeCity['user_location']),
                    'slug' => 'hip-hop'
                ])
                ->route('genre', 'Jazz', [
                    'location' => Slugify::run($activeCity['user_location']),
                    'slug' => 'jazz'
                ])
                ->route('genre', 'Classical', [
                    'location' => Slugify::run($activeCity['user_location']),
                    'slug' => 'classical'
                ])
                ->route('genre', 'Folk', [
                    'location' => Slugify::run($activeCity['user_location']),
                    'slug' => 'folk'
                ])
                ->route('genre', 'Blues', [
                    'location' => Slugify::run($activeCity['user_location']),
                    'slug' => 'blues'
                ])
                ->route('genre', 'Metal', [
                    'location' => Slugify::run($activeCity['user_location']),
                    'slug' => 'metal'
                ])
                ->route('genre', 'Dance/Electronic', [
                    'location' => Slugify::run($activeCity['user_location']),
                    'slug' => 'dance-electronic'
                ])
        )
        ->submenu(
            $sportsUrl,
            Menu::new()
                ->addClass('submenu')
                ->route('genre', 'Baseball', [
                    'location' => Slugify::run($activeCity['user_location']),
                    'slug' => 'baseball'
                ])
                ->route('genre', 'Football', [
                    'location' => Slugify::run($activeCity['user_location']),
                    'slug' => 'football'
                ])
                ->route('genre', 'Basketball', [
                    'location' => Slugify::run($activeCity['user_location']),
                    'slug' => 'basketball'
                ])
                ->route('genre', 'Tennis', [
                    'location' => Slugify::run($activeCity['user_location']),
                    'slug' => 'tennis'
                ])
        )
        ->setActiveFromRequest();
});

Menu::macro('footer', function () {
    $activeCity = GetActiveCity::run();
    return Menu::new()
        ->route('home', 'Home', [
            'location' => Slugify::run($activeCity['user_location']),
        ])
        ->setActiveFromRequest();
});




Route::get('display-user', [UserController::class, 'index']);

Route::get('post/{slug}', function ($slug) {
    $post = Post::where('slug', '=', $slug)->firstOrFail();
    return view('post', compact('post'));
});

Route::controller(SearchController::class)->group(function () {
    Route::get('demo-search', 'index');
    Route::get('autocomplete', 'autocomplete')->name('autocomplete');
});

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap.index');
Route::get('/sitemap/events.xml', [EventSitemapController::class, 'index'])->name('sitemap.events.index');
Route::get('/sitemap/events/{letter}.xml', [EventSitemapController::class, 'show'])->name('sitemap.events.show');
Route::get('/sitemap/eventsCity.xml', [EventCitySitemapController::class, 'index'])->name('sitemap.eventsCity.index');
Route::get('/sitemap/eventsCity.xml', [EventCitySitemapController::class, 'show'])->name('sitemap.eventsCity.show');
