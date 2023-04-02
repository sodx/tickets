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
    <div class="content-wrapper">
        <div class="content-container">
            <div class="attraction-info">
                <section id="map" class="content-section">
                    @include('partials.content-block', [
                        'title' => 'Map',
                        'content' => $venue->googleMap()
                    ])
                </section>
                <section id="venue-info" class="content-section">
                    @include('partials.content-block', [
                        'title' => 'Venue Info',
                        'content' => '<b>Address:</b> ' . $venue->state . ' ' . $venue->city . ' ' . $venue->address . '<br>' . $venue->seo_content
                    ])
                </section>
            </div>
            @if(count($venue->upcomingEvents) > 0)
                <section id="events" class="content-section">
                    <h2>Upcoming Events In {{ $venue->name }}</h2>
                    @include('partials.events-container', ['events' => $venue->upcomingEvents])
                </section>
            @endif
        </div>
    </div>
@endsection
