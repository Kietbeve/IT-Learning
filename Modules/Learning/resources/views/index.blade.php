@extends('learning::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('learning.name') !!}</p>
@endsection
