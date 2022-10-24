@extends('layouts.app')

@section('title', 'HomePage')

@section('sidebar')
    @parent
@endsection

@section('content')
    @if($posts)
        <div class="container">
            <div class="row">
                @foreach($posts as $post)
                    <div class="col-md-3">
                        <a href="/post/{{ $post->slug }}">
                            <img src="{{ Voyager::image( $post->image ) }}" style="width:100%">
                            <span>{{ $post->title }}</span>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
@endsection
