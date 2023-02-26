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
    @include('partials.featured-event', ['event' => $featuredEvent])
    <div class="content-container">
        <h1 class="page-heading">{{$h1}}</h1>
        {{ Breadcrumbs::render() }}
        @if($topViewed && count($topViewed) > 1)
            <section class="page-section">
                <h2>Most Popular Upcoming Events</h2>
                @include('partials.events-container', ['events' => $topViewed])
            </section>
        @endif
        @if($events && count($events) > 1 && count($events) >= count($topViewed))
            <section class="page-section">
                <h2>All Events</h2>
                @include('partials.events-container', $events)
                @if(count($events) > 7)
                    <div class="btn-wrapper">
                        <a href="{{route('events', $slugify->handle( $location ) )}}" class="btn">Load More Events</a>
                    </div>
                @endif
            </section>
            @if(isset($links))
                {!! $links !!}
            @endif
        @endif
        @if($tours)
            <section class="page-section">
                <h2>All Tours in {{$location}}</h2>
                @include('partials.tours-container', $tours)
                @if(count($tours) > 3)
                    <div class="btn-wrapper">
                        <a href="" class="btn">Load More Tours</a>
                    </div>
                @endif
            </section>
        @endif
    </div>
@endsection
