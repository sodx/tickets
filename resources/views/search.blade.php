@extends('layouts.app')
@section('title', 'HomePage')
@section('sidebar')
    @parent
@endsection
@inject('slugify', 'App\Actions\Slugify')
@inject('activeCity', 'App\Actions\GetActiveCity')
@php
    $activeCity = $activeCity->handle();
@endphp
@section('content')
    <div class="content-container">
        @if(
    ($eventsInUserCity &&  count($eventsInUserCity) > 0) ||
    ($attractions && count($attractions) > 0))
            <section class="page-section">
                <h1>Search Results for {{$searchTerm}}</h1>
                @if($eventsInUserCity && count($eventsInUserCity) > 0)
                    <section class="section">
                        <h2>Events</h2>
                        @include('partials.events-container', ['events' => $eventsInUserCity])
                    </section>
                @endif
                @if($attractions && count($attractions) > 0)
                    <section class="section">
                        <h2>Attractions</h2>
                        @include('partials.attractions', ['attractions' => $attractions])
                    </section>
                @endif
            </section>
            @else
                <h3 class="text-center mt-5">No Results Found :(</h3>
            @endif
        @if(isset($links))
            {!! $links !!}
        @endif
    </div>
@endsection
