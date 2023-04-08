@extends('layouts.app')
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
        <h1 class="page-heading">{{$h1}}</h1>
        <div class="error-text">
            <p class="error-text--bigger">{{$subheading}}</p>
            <p>{{$text}}</p>
        </div>
    </div>
@endsection
