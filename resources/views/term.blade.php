@extends('layouts.app')

@section('title', $term->name)

@section('content')
    <div class="content-wrapper">
        <div class="content-container">
            @if(count($term->filterEventByLocation($location)) > 0)
                <section id="events" class="content-section">
                    <h2>Upcoming Events In {{ $term->name }}</h2>
                    @include('partials.events-container', ['events' => $term->filterEventByLocation($location)])
                </section>
            @endif
        </div>
    </div>
@endsection
