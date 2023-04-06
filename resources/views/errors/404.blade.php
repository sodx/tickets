@extends('layouts.app')
@php
    $title = 'Page Not Found | Liveconcerts';
@endphp
@section('title', $title)
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
        <h1 class="page-heading error-heading">404</h1>
        <div class="error-text">
            <p class="error-text--bigger">Sorry, the page you are looking for could not be found.</p>
            <p>Go to the <a href="{{route('home')}}">Homepage</a></p>
        </div>
    </div>
@endsection
