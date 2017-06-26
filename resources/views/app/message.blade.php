@extends('master')

@section('content')

<h1>This is for displaying flash messages.</h1>

<div class="alert alert-{!! $message->type !!}">
    {!! $message->text !!}
</div>

@endsection
