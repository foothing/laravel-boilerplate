@extends('master')

@section('content')

<h1>Sign the fuck up</h1>

<section>

    @if ($errors->count())
    <div class="alert alert-danger">
        @foreach($errors->all() as $error)
        {!! $error !!}
        @endforeach
    </div>
    @endif

    <form name="register" method="post" action="/auth/register">
        {!! csrf_field() !!}

        <div class="form-group">
            <input type="text" value="{!! old('email') !!}" name="email" class="form-control" placeholder="Email">
        </div>

        <div class="form-group">
            <input type="password" name="password" class="form-control" placeholder="Password">
        </div>

        <div class="form-group">
            <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat Password">
        </div>

        {!! Recaptcha::render() !!}

        <button type="submit" class="btn">Register</button>
    </form>


</section>

@endsection
