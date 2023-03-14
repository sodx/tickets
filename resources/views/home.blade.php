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
    @if(isset($featuredEvent))
        @include('partials.featured-event', ['event' => $featuredEvent])
    @endif
    <div class="content-container">
        <h1 class="page-heading">{{$h1}}</h1>
        {{ Breadcrumbs::render() }}
        @if(isset($topViewed) && $topViewed && count($topViewed) > 1)
            <section class="page-section">
                <h2>Most Popular Upcoming Events</h2>
                @include('partials.events-container', ['events' => $topViewed])
            </section>
        @endif
        @if((count($events) >= count($topViewed)))
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
    </div>
@endsection
