@extends('layouts.app')

@section('title', 'HomePage')

@section('sidebar')
    @parent

    <p>This is appended to the master sidebar.</p>
@endsection

@section('content')
    @foreach($posts as $post)
        <div class="col-md-3">
            <a href="/post/{{ $post->slug }}">
                <img src="{{ Voyager::image( $post->image ) }}" style="width:100%">
                <span>{{ $post->title }}</span>
            </a>
        </div>
    @endforeach
@endsection
