@extends('layouts.app')

@inject('activeCity', 'App\Actions\GetActiveCity')
@php
    $activeCity = $activeCity->handle();
@endphp
@section('content')
    <div class="content-wrapper">
        <div class="content-container">
            <b>Saved Events:</b> {{ $saved }}
            <b>Updated Events:</b> {{ $updated }}
            <b>Processed Events:</b> {{ $processed }}
        </div>
    </div>
@endsection
