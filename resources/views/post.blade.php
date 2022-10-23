@extends('layouts.app')

@section('title', $post->title)

@section('sidebar')
    @parent

    <p>This is appended to the master sidebar.</p>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <h1>{{ $post->title }}</h1>
                <img src="{{ Voyager::image( $post->image ) }}" style="width:100%">
                <p>{!! $post->body !!}</p>
            </div>
        </div>
    </div>
@endsection

