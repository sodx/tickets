<?php

use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
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
    return $eventController->index($location, 'city');
})->name('home');


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

Route::get('/city/{location}/date/{date}', function ($location, $date) {
    $unslugify = new Unslugify();
    $location = $unslugify->handle($location);
    $eventController = new EventController();
    return $eventController->index($location, 'city', $date);
})->name('date');

Route::get('/city/{location}/date/{date}/date_to/{date_to}', function ($location, $date, $date_to) {
    $unslugify = new Unslugify();
    $location = $unslugify->handle($location);
    $eventController = new EventController();
    return $eventController->index($location, 'city', $date, $date_to);
})->name('date');


/**
 * =====================
 * Single Event
 * =====================
 */
Route::get('/city/{location}/date/{date}/event/{slug}', function ($location, $date, $slug) {
    $event = App\Models\Event::where('slug', '=', $slug)->firstOrFail();
    return view('event', compact('event'));
})->name('event');


/**
 * =====================
 * Single Venue
 * =====================
 */
Route::get('venue/{slug}', function ($slug) {
    $venue = App\Models\Venue::where('slug', '=', $slug)->firstOrFail();
    return view('venue', compact('venue'));
})->name('venue');


/**
 * =====================
 * Single Atraction
 * =====================
 */
Route::get('attraction/{slug}', function ($slug) {
    $attraction = App\Models\Attraction::where('slug', '=', $slug)->firstOrFail();
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
    return Menu::new()
        ->route('home', 'Home', [
            'location' => Slugify::run($activeCity['user_location']),
            'type' => $activeCity['user_location_type']
        ])
        ->route('segment', 'Concerts', [
            'location' => Slugify::run($activeCity['user_location']),
            'slug' => 'music'
        ])
        ->route('genre', 'Country', [
            'location' => Slugify::run($activeCity['user_location']),
            'slug' => 'country'
        ])
        ->setActiveFromRequest();
});




Route::get('display-user', [UserController::class, 'index']);

Route::get('post/{slug}', function ($slug) {
    $post = Post::where('slug', '=', $slug)->firstOrFail();
    return view('post', compact('post'));
});

Route::controller(SearchController::class)->group(function(){
    Route::get('demo-search', 'index');
    Route::get('autocomplete', 'autocomplete')->name('autocomplete');
});
