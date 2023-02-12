@extends('layouts.app')

@section('title', $term->name)
@inject('activeCity', 'App\Actions\GetActiveCity')
@php
    $activeCity = $activeCity->handle();
@endphp
@section('content')
    <div class="content-wrapper">
        <div class="content-container">
            @php
                $events = $term->filterEventByLocation($location);
            @endphp
            @if(count($events) > 0)
                <section id="events" class="content-section">
                    <h1>{{ $term->name }} Events in {{$activeCity['user_location']}}</h1>
                    @include('partials.events-container', ['events' => $events['single']])
                    @foreach($events['tour'] as $event_tour)
                        @include('partials.events-list', ['events' => $event_tour ?? []])
                    @endforeach
                </section>
            @endif
        </div>
    </div>
@endsection
