@extends('master')

@section('content')

<h1>So, you forgot your password eh.</h1>

<section>

    @if ($errors->count())
    <div class="alert alert-danger">
        @foreach($errors->all() as $error)
        {!! $error !!}
        @endforeach
    </div>
    @endif

    <form name="password" method="post" action="/auth/password">
        {!! csrf_field() !!}

        <div class="form-group">
            <input type="text" value="{!! old('email') !!}" name="email" class="form-control" placeholder="Email">
        </div>

        <button type="submit" class="btn">Reset password</button>
    </form>


</section>

@endsection
