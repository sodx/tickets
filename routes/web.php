<?php

use Illuminate\Support\Facades\Route;

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

use App\Http\Controllers\TicketMasterController;
use App\Models\Post;

Route::get('/', function () {
    $posts = Post::all();
    return view('home', compact('posts'));
});

Route::get('post/{slug}', function ($slug) {
    $post = Post::where('slug', '=', $slug)->firstOrFail();
    return view('post', compact('post'));
});

Route::get('perform', function () {
    $tt = new TicketMasterController();
    $tt->index();
});

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
