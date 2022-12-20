@extends('layouts.app')

@section('title', $term->name)

@section('content')
    <div class="content-wrapper">
        <div class="content-container">
            @php
                $events = $term->filterEventByLocation($location);
            @endphp
            @if(count($events) > 0)
                <section id="events" class="content-section">
                    <h2>Upcoming Events In {{ $term->name }}</h2>
                    @include('partials.events-container', ['events' => $events['single']])
                    @include('partials.events-list', ['events' => $events['tour'][0] ?? []])
                </section>
            @endif
        </div>
    </div>
@endsection
