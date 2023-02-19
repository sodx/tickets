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
        @if($attractions)
            <section class="page-section">
                <h2>All Attractions</h2>
                <section class="section">
                    @include('partials.attractions', ['attractions' => $attractions])
                </section>
            </section>
        @endif
            @if(isset($links))
                {!! $links !!}
            @endif
    </div>
@endsection

