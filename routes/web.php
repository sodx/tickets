<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TicketMasterController;
use App\Models\Post;

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

Route::get('/', function () {
    $events = App\Models\Event::latest()->take(140)->get();
    return view('home', compact('events'));
});

Route::get('post/{slug}', function ($slug) {
    $post = Post::where('slug', '=', $slug)->firstOrFail();
    return view('post', compact('post'));
});

Route::get('event/{slug}', function ($slug) {
    $event = App\Models\Event::where('slug', '=', $slug)->firstOrFail();
    return view('event', compact('event'));
});

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Route::get('perform', function () {
    $tt = new TicketMasterController();
    $tt->index();
});
