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

        <link href="/dist/bootstrap.min.css" rel="stylesheet" type="text/css">
        <!-- <link href="/dist/libs.min.css" rel="stylesheet" type="text/css"> -->
    </head>

    <body>
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <h1>Boilerplate</h1>

                    <span uib-tooltip="A tooltip">
                        <i class="glyphicon-pencil glyphicon"></i>
                        A tooltip
                    </span>


                </div>
            </div>
        </div>

        <div ui-view></div>

        <script src="dist/libs.min.js?<?=$_v?>"></script>
        <script src="dist/main.min.js?<?=$_v?>"></script>
    </body>

</html>
