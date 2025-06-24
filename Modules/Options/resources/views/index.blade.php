@extends('options::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('options.name') !!}</p>
@endsection
