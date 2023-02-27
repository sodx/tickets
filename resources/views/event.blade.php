@extends('layouts.app')
@inject('slugify', 'App\Actions\Slugify')
@inject('activeCity', 'App\Actions\GetActiveCity')
@php
    $activeCity = $activeCity->handle();
@endphp
@section('title', $event->title)
@section('content')
    <div class="event-item" data-id="{{$event->event_id}}"></div>
    <figure class="event-poster">
        <div class="event-poster__image-wrapper" id="parallax-scene">
            <div class="event-poster__image">
                <link rel="preload" as="image" href="{{$event->poster}}" />
                <img src="{{ $event->poster }}" data-src="{{ $event->poster }}" alt="{{ $event->name }}" loading="lazy">
            </div>
        </div>
        <figcaption class="event-poster__meta">
            <div class="content-container">
                <div class="event-poster__title-block">
                    <h1 class="event-poster__title">
                        {{ $event->name }}
                    </h1>
                    <div class="event-poster__favorites">
                        <button class="add_to_favorites {{ $event->isFavorite() ? 'active' : ''  }}" data-id="{{ $event->event_id }}"><span class="material-symbols-outlined">favorite</span></button>
                    </div>
                </div>
                <div class="event-meta">
                    @if( $event->segment )
                        <a class="event-meta__link" title="{{ $event->segment->name }}" href="{{ route('segment', ['slug' => $event->segment->slug, 'location' => $slugify->handle($activeCity['user_location'])]) }}">
                            {{ $event->segment->name }}
                        </a>
                    @endif
                    @if( $event->genre )
                        <a class="event-meta__link" title=""  href="{{ route('genre', ['slug' => $event->genre->slug, 'location' => $slugify->handle($activeCity['user_location']) ]) }}">
                            {{ $event->genre->name }}
                        </a>
                    @endif
                </div>
                {!! Breadcrumbs::view('breadcrumbs::json-ld', 'event', $slugify->handle($event->venue->city), $event->segment->slug, $event->slug); !!}
                {{ Breadcrumbs::render('event', $slugify->handle($event->venue->city), $event->segment->slug, $event->slug) }}
            </div>
        </figcaption>
    </figure>
    <nav class="page-nav">
        <ul>
            @if($event->info || $event->pleaseNote)
                <li>
                    <a href="#event-info">Event Info</a>
                </li>
            @endif
            @if($event->attractions)
                <li>
                    <a href="#attractions">Attractions</a>
                </li>
            @endif
            @if($event->venue)
                <li>
                    <a href="#venue">Venue</a>
                </li>
            @endif
        </ul>
    </nav>
    <div class="content-wrapper">
        <div class="content-container">
            @if($event->info || $event->pleaseNote)
                <section id="event-info" class="content-section">
                    @if($event->info)
                        @include('partials.content-block', [
                            'title' => 'Event Info',
                            'content' => $event->info
                        ])
                    @endif
                    @if($event->pleaseNote && $event->info !== $event->pleaseNote)
                        @include('partials.content-block', [
                            'title' => 'Please Note',
                            'content' => $event->pleaseNote
                        ])
                    @endif
                </section>
            @endif
                @if($event->venue)
                    <section id="venue" class="content-section">
                        <h2>Venue</h2>
                        @include('partials.venue-card', ['venue' => $event->venue])
                        @if($event->seatmap)
                            @include('partials.content-block', [
                                'title' => 'Seatmap',
                                'image' => $event->seatmap,
                                'imageLink' => $event->url
                            ])
                        @endif
                    </section>
                @endif
                @if($event->attractions)
                    @include('partials.attractions-videos', ['attractions' => $event->attractions])
                @endif
            @if($event->attractions)
                <section id="attractions" class="content-section">
                    <h2>Attractions</h2>
                    @include('partials.attractions', ['attractions' => $event->attractions])
                </section>
            @endif
        </div>
        <aside class="sticky-sidebar">
            <ul class="event-sidebar">
                <li class="event-sidebar__date">
                    <span class="material-symbols-outlined">calendar_month</span>
                    <span class="event-sidebar__date-day">{{ $event->start_date->format('M d Y') }}</span>
                    <span class="event-sidebar__date-time">{{ $event->start_time->format('H:i') }}</span>
                </li>
                <li class="event-sidebar__price">
                    <span class="material-symbols-outlined">map</span>
                    <span
                        class="event-sidebar__item"><b>{{ $event->venue->city }}, {{ $event->venue->state_code }} </b></span>
                </li>
                <li class="event-sidebar__price">
                    <span class="material-symbols-outlined">home_pin</span>
                    <span
                        class="event-sidebar__item">{{ $event->venue->address }}</span>
                </li>
                <li class="event-sidebar__venue">
                    <span class="material-symbols-outlined">location_on</span>
                    <a class="event-sidebar__item" href="{{ route('venue', $event->venue->slug) }}">{{ $event->venue->name }}</a>
                </li>
                @if( $event->price_min )
                    <li class="event-sidebar__price">
                        <span class="material-symbols-outlined">payments</span>
                        <span
                            class="event-sidebar__item">From <b>{{ $event->price_min }} {{ $event->price_currency }}</b></span>
                    </li>
                @endif
                <li class="event-sidebar__tickets">
                    <a class="event-sidebar__link btn" href="{{ $event->url }}" target="_blank">Buy Tickets</a>
                </li>
            </ul>
        </aside>
    </div>
    @include('partials.related-events', $relatedEvents)
@endsection

@section('schema')
    {!! $schema !!}
@endsection

