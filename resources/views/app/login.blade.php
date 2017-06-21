@extends('master')

@section('content')

<h1>Log the fuck in</h1>

<section>

    @if ($errors->count())
    <div class="alert alert-danger">
        @foreach($errors->all() as $error)
            {!! $error !!}
        @endforeach
    </div>
    @endif

    @if (Session::has('message'))
    <div class="alert alert-success">
        {!! Session::get('message') !!}
    </div>
    @endif

    <form name="login" method="post" action="/auth/login">
        {!! csrf_field() !!}

        <div class="form-group">
            <input type="text" value="{!! old('email') !!}" name="email" class="form-control" placeholder="Email">
        </div>

        <div class="form-group">
            <input type="password" name="password" class="form-control" placeholder="Password">
        </div>

        <button type="submit" class="btn">Login</button>
    </form>


</section>

@endsection
