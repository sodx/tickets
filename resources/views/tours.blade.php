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
        @if($tours)
            <section class="page-section">
                <h2>All Tours</h2>
                <section class="section">
                    <div class="events-container">
                    @foreach($tours as $tour)
                        @include('partials.tour-card', ['event' => $tour[0], 'count' => count($tour)])
                    @endforeach
                    </div>
                </section>
            </section>
        @endif
    </div>
@endsection
