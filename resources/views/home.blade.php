@extends('layouts.app')

@section('title', 'HomePage')

@section('sidebar')
    @parent
@endsection

@section('content')
    <div class="content-container">
        @include('partials.events-container', $events)
    </div>
@endsection
