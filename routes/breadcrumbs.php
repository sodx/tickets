<?php // routes/breadcrumbs.php

// Note: Laravel will automatically resolve `Breadcrumbs::` without
// this import. This is nice for IDE syntax and refactoring.
use App\Actions\Unslugify;
use Diglactic\Breadcrumbs\Breadcrumbs;

// This import is also not required, and you could replace `BreadcrumbTrail $trail`
//  with `$trail`. This is nice for IDE type checking and completion.
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Home
Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push('Home', route('home'));
});

// Home > [City Name]
Breadcrumbs::for('city', function (BreadcrumbTrail $trail, $location) {
    $trail->parent('home');
    $unslugify = new Unslugify();
    $trail->push($unslugify->handle($location), route('city', $location));
});

// Home > [City Name] > Events
Breadcrumbs::for('events', function (BreadcrumbTrail $trail, $location) {
    $trail->parent('city', $location);
    $trail->push('Events', route('city', $location));
});

// Home > [City Name] > [Segment Name]
Breadcrumbs::for('segment', function (BreadcrumbTrail $trail, $location, $segment) {
    $trail->parent('city', $location);
    $unslugify = new Unslugify();
    $trail->push($unslugify->handle($segment), route('segment', ['location' => $location, 'slug' => $segment]));
});

// Home > [City Name] > [Segment Name] > [Event Name]
Breadcrumbs::for('event', function (BreadcrumbTrail $trail, $location, $segment, $event) {
    $trail->parent('segment', $location, $segment);
    $unslugify = new Unslugify();
    $event = \App\Models\Event::where('slug', $event)->first();
    $trail->push($event->name, route('event', ['location' => $location, 'segment' => $segment, 'slug' => $event]));
});

Breadcrumbs::for('genre', function (BreadcrumbTrail $trail, $location, $genre) {
    $trail->parent('city', $location);
    $unslugify = new Unslugify();
    $trail->push($unslugify->handle($genre), route('genre', ['location' => $location, 'slug' => $genre]));
});
