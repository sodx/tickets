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
    @include('partials.featured-event', ['event' => $featuredEvent])
    <div class="content-container">
        <h1 class="page-heading">{{$h1}}</h1>
        {!! Breadcrumbs::view('breadcrumbs::json-ld'); !!}
        {{ Breadcrumbs::render() }}
        @if($events)
            <section class="page-section">
                <h2>Events</h2>
                <div class="sorting">
                    <div class="sorting-item">
                        <span>Results Per Page:</span>
                        <select name="per_page" id="" class="sort">
                            <option value="20" {{ request()->query('per_page') === '20' ? 'selected="selected"' : '' }}>20</option>
                            <option value="40" {{ request()->query('per_page') === '40' ? 'selected="selected"' : '' }}>40</option>
                            <option value="80" {{ request()->query('per_page') === '80' ? 'selected="selected"' : '' }}>80</option>
                        </select>
                    </div>
                    <div class="sorting-item">
                        <span>Order By:</span>
                        <select name="sort" id="" class="sort">
                            <option value="date" {{ request()->query('sort') === 'date' ? 'selected="selected"' : '' }}>Start Date</option>
                            <option value="views" {{ request()->query('sort') === 'views' ? 'selected="selected"' : '' }}>Popular</option>
                            <option value="name" {{ request()->query('sort') === 'name' ? 'selected="selected"' : '' }}>Name</option>
                        </select>
                    </div>
                    <div class="sorting-item">
                        <span>Event Dates:</span>
                        <input type="text" class="datefrom" placeholder="Start Date" value="{{request()->query('date')}}">
                        <input type="text" class="dateto" placeholder="End Date" value="{{request()->query('date_to')}}">
                    </div>
                </div>
                @if($events->count() > 0)
                    @include('partials.events-container', $events)
                @else
                    <h3 class="text-center mt-5">No Events Found :(</h3>
                @endif
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
