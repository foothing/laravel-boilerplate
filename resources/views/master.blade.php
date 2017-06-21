<?php include(base_path('revision')) ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel Boilerplate</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

    <link href="/assets/css/libs.min.css" rel="stylesheet" type="text/css">
</head>

<body>
<div class="container">
    <div class="row">
        <div class="col-xs-12">

            <ul>
                <li><a href="/auth/login">Login</a></li>
                <li><a href="/auth/register">Register</a></li>
            </ul>

            @yield('content')

        </div>
    </div>
</div>

<script src="/assets/js/libs.min.js?<?=$_v?>"></script>
<script src="/assets/js/main.min.js?<?=$_v?>"></script>
</body>

</html>
