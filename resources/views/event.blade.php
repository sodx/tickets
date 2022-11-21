@extends('layouts.app')

@section('title', $event->title)

@section('content')
    <figure class="event-poster">
        <div class="event-poster__image-wrapper">
            <div class="event-poster__image" style="background-image:url({{ $event->poster }})"></div>
        </div>
        <figcaption class="event-poster__meta">
            <div class="content-container">
                <h1 class="event-poster__title">
                    {{ $event->name }}
                </h1>
                <div class="event-meta">
                    <a class="event-meta__link" title="">
                        {{ $event->segment->name }}
                    </a>
                    <a class="event-meta__link" title="">
                        {{ $event->genre->name }}
                    </a>
                </div>
            </div>
        </figcaption>
    </figure>
    <div class="content-wrapper">
        <div class="content-container">
            @if($event->info)
                @include('partials.content-block', [
                    'title' => 'Event Info',
                    'content' => $event->info
                ])
            @endif
                @if($event->info)
                    @include('partials.content-block', [
                        'title' => 'Please Note',
                        'content' => $event->pleaseNote
                    ])
                @endif
        </div>
        <aside class="sticky-sidebar">

        </aside>
    </div>
@endsection

