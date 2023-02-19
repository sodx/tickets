@extends('layouts.app')
@section('sidebar')
    @parent
@endsection
@section('content')
    <div class="content-container">
        <section class="page-section">
            <h1>{{ $post->title }}</h1>
            <img src="{{ Voyager::image( $post->image ) }}" style="width:100%">
            <p>{!! $post->body !!}</p>
        </section>
    </div>
@endsection


