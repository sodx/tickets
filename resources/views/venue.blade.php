@extends('layouts.app')

@section('title', $venue->name)

@section('content')
    <figure class="venue-poster">
        <div class="venue-poster__image-wrapper">
            <div class="venue-poster__image" style="background-image:url({{ $venue->image }})"></div>
        </div>
        <figcaption class="venue-poster__meta">
            <div class="content-container">
                <h1 class="venue-poster__title">
                    {{ $venue->name }}
                </h1>
            </div>
        </figcaption>
    </figure>
    <nav class="page-nav">
        <ul>
            <li>
                <a href="#venue-info">Venue Info</a>
            </li>
            <li>
                <a href="#map">Map</a>
            </li>
            @if(count($venue->upcomingEvents) > 0)
                <li>
                    <a href="#events">Events</a>
                </li>
            @endif
        </ul>
    </nav>
    <div class="content-wrapper">
        <div class="content-container">
            <section id="venue-info" class="content-section">
                @include('partials.content-block', [
                    'title' => 'Venue Info',
                    'content' => $venue->state . ' ' . $venue->city . ' ' . $venue->address
                ])
            </section>
            <section id="map" class="content-section">
                @include('partials.content-block', [
                    'title' => 'Map',
                    'content' => $venue->googleMap()
                ])
            </section>
            @if(count($venue->upcomingEvents) > 0)
                <section id="events" class="content-section">
                    <h2>Upcoming Events In {{ $venue->name }}</h2>
                    @include('partials.events-container', ['events' => $venue->upcomingEvents])
                </section>
            @endif
        </div>
    </div>
@endsection
