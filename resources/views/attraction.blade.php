@extends('layouts.app')

@section('title', $attraction->name)
@php
    ray($attraction->events);
@endphp
@section('content')
    <figure class="event-poster">
        <div class="event-poster__image-wrapper"  id="parallax-scene">
            <div class="event-poster__image" style="background-image:url({{ $attraction->poster }})"></div>
        </div>
        <figcaption class="event-poster__meta" data-depth="0.2">
            <div class="content-container">
                <h1 class="event-poster__title">
                    {{ $attraction->name }}
                </h1>
            </div>
        </figcaption>
    </figure>
    <nav class="page-nav">
        <ul>
            <li>
                <a href="#event-info">Event Info</a>
            </li>
        </ul>
    </nav>
    <div class="content-wrapper">
        <div class="content-container">
            @if(count($attraction->upcomingEvents) > 0)
                <section id="events" class="content-section">
                    <h2>Upcoming Events In {{ $attraction->name }}</h2>
                    @include('partials.events-container', ['events' => $attraction->upcomingEvents])
                </section>
            @endif
            @include('partials.attractions-videos', ['attractions' => [$attraction]])
        </div>
    </div>
@endsection

