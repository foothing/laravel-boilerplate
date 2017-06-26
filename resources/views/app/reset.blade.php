@extends('master')

@section('content')

<h1>Set your new password</h1>

<section>

    @if ($errors->count())
    <div class="alert alert-danger">
        @foreach($errors->all() as $error)
        {!! $error !!}
        @endforeach
    </div>
    @endif

    <form name="password" method="post" action="/auth/reset">
        {!! csrf_field() !!}

        <input type="hidden" name="token" value="{!! $token !!}">

        <div class="form-group">
            <input type="password" name="password" class="form-control" placeholder="Password">
        </div>

        <div class="form-group">
            <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat Password">
        </div>

        <button type="submit" class="btn">Reset password</button>
    </form>


</section>

@endsection
