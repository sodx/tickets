@extends('layouts.app')

@section('title', 'HomePage')

@section('sidebar')
    @parent
@endsection
@inject('activeCity', 'App\Actions\GetActiveCity')
@php
    $activeCity = $activeCity->handle();
@endphp
@section('content')
    <div class="content-container">
        <h1 class="page-heading">{{$h1}}</h1>
        {!! Breadcrumbs::view('breadcrumbs::json-ld'); !!}
        {{ Breadcrumbs::render() }}
        @if($topViewed)
            <section class="page-section">
                <h2>Most Popular Upcoming Events</h2>
                @include('partials.events-container', ['events' => $topViewed])
            </section>
        @endif
        @if($events)
            <section class="page-section">
                <h2>All Events</h2>
                @include('partials.events-container', $events)
                <div class="btn-wrapper">
                <a href="{{route('events', $activeCity['user_location'] )}}" class="btn">Load More Events</a>
                </div>
            </section>
            @if(isset($links))
                {!! $links !!}
            @endif
        @endif
        @if($tours)
            <section class="page-section">
                <h2>All Tours in {{$activeCity['user_location']}}</h2>
                @include('partials.tours-container', $tours)
                <div class="btn-wrapper">
                    <a href="" class="btn">Load More Tours</a>
                </div>
            </section>
        @endif
    </div>
@endsection
