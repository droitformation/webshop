<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="@Designpond | Cindy Leschaud">
    <title>Droit Formation | Administration</title>
    <meta name="description" content="Administration">
    <link rel="stylesheet" type="text/css" href="<?php echo asset('backend/css/styles.css');?>">
    <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600' rel='stylesheet' type='text/css'>
</head>
<body class="focusedform">
    <div class="verticalcenter">

        <!-- messages and errors -->
        @include('backend.partials.message')

        <h1 class="auth-logo text-center"><a href="{{ url('/') }}">Droit Formation | Administration</a></h1>
        <div class="panel panel-primary">

            <!-- Contenu -->
            @yield('content')
           <!-- Fin contenu -->

        </div>

    </div>
</body>
</html>
