@extends('layouts.app')

@section('title', 'HomePage')

@section('sidebar')
    @parent
@endsection

@section('content')
    {{ $output }}
@endsection
