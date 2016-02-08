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
    <link rel="stylesheet" type="text/css" href="<?php echo asset('css/validation.css');?>">
    <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600' rel='stylesheet' type='text/css'>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/jquery.validate.min.js"></script>
    <script src="<?php echo asset('backend/js/validation/messages_fr.js');?>"></script>
    <script src="<?php echo asset('js/validation.js');?>"></script>
</head>
<body class="focusedform">
    <div class="verticalcenter">

        <!-- messages and errors -->
        @include('backend.partials.message')

        <div class="panel panel-inverse">
            <!-- Contenu -->
            @yield('content')
           <!-- Fin contenu -->
        </div>

    </div>
</body>
</html>
