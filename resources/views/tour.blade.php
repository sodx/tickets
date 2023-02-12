@extends('layouts.app')

@section('title', $tour->name)
@section('content')
    <figure class="event-poster">
        <div class="event-poster__image-wrapper" id="parallax-scene">
            <div class="event-poster__image" style="background-image:url({{ $tour->upcomingEvents[0]->poster }})"></div>
        </div>
        <figcaption class="event-poster__meta">
            <div class="content-container">
                <h1 class="event-poster__title">
                    {{ $tour->name }}
                </h1>
                <h2>Upcoming Tour List</h2>
            </div>
        </figcaption>
    </figure>
    <div class="content-wrapper">
        <div class="content-container">
            @if(count($tour->upcomingEvents) > 0)
                <section id="events" class="content-section">
                    @include('partials.events-list', ['events' => $tour->upcomingEvents])
                </section>
            @endif
        </div>
    </div>
@endsection
