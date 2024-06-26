@extends('layouts.app')
@inject('slugify', 'App\Actions\Slugify')
@inject('activeCity', 'App\Actions\GetActiveCity')
@php
    $activeCity = $activeCity->handle();
@endphp
@section('title', $event->title)
@section('content')
    <div class="event-item" data-id="{{$event->event_id}}"></div>
    <figure class="event-poster event-poster--{{$event->status}}">
        @if($event->status === 'inactive')
            <div class="notification">
                This event has started or has already happened, so ticket sales have stopped.
            </div>
        @elseif($event->status === 'soldout')
            <div class="notification">
                This event has sold out.
            </div>
        @elseif($event->status === 'canceled')
            <div class="notification">
                This event has been canceled.
            </div>
        @elseif($event->status === 'postponed')
            <div class="notification">
                This event has been postponed.
            </div>
        @elseif($event->status === 'rescheduled')
            <div class="notification">
                This event has been rescheduled.
            </div>
        @elseif($event->status === 'ended')
            <div class="notification">
                This event has ended.
            </div>
        @elseif($event->status === 'offsale')
            <div class="notification">
                This event is no longer on sale.
            </div>
        @endif
        <div class="event-poster__image-wrapper" id="parallax-scene">
            <div class="event-poster__image">
                <link rel="preload" href="{{$event->poster}}" as="image">
                <picture>
                    <source
                        media = "(min-width:1280px)"
                        srcset="{{$event->poster}} 1280w">
                    <source
                        media = "(min-width:340px)"
                        srcset = "{{$event->medium_image}} 340w" >
                    <source
                        media = "(min-width:300px)"
                        srcset = "{{$event->thumbnail}} 300w" >
                    <img src="{{$event->medium_image}}" alt="{{$event->name}}">
                </picture>
            </div>
        </div>
        <figcaption class="event-poster__meta">
            <div class="content-container">
                <div class="event-poster__title-block">
                    <h1 class="event-poster__title">{{ $event->name }} In {{$event->venue->city}}</h1>
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
    <div class="content-wrapper">
        <div class="content-container">
            @php($videos = false)
            @foreach($event->attractions as $attraction)
                @if($attraction->haveVideos() !== false)
                    @php($videos = $attraction->haveVideos())
                @endif
            @endforeach
            @php(ray($videos))

            @if($event->info || $event->pleaseNote)
                <section id="event-info" class="content-section">
                    <div class="attraction-info {{ $videos === false ? "attraction-info--fullwidth" : ""  }}">
                        @if($event->info)
                        @include('partials.content-block', [
                                'title' => 'Event Info',
                                'content' => $event->info
                            ])
                        @endif
                        @if($videos === true)
                            <div class="content-block">
                            @include('partials.attractions-videos', ['attractions' => $event->attractions])
                            </div>
                        @endif
                    </div>
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
                        @include('partials.venue-card', ['venue' => $event->venue])
                    </section>
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
                @if($event->status === 'onsale')
                    <li class="event-sidebar__tickets">
                        <a class="event-sidebar__link btn" rel="nofollow" href="{{ $event->affUrl() }}" target="_blank">Buy Tickets</a>
                    </li>
                @endif
            </ul>
        </aside>
    </div>
    @include('partials.related-events', $relatedEvents)
@endsection

@section('schema')
    {!! $schema !!}
@endsection

