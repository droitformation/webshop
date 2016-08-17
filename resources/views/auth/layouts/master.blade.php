<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="@Designpond | Cindy Leschaud">
    <title>Droit Formation | Administration</title>
    <meta name="description" content="Administration">
    <meta name="_token" content="<?php echo csrf_token(); ?>">

    <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="<?php echo asset('backend/css/styles.css');?>">
    <link rel="stylesheet" type="text/css" href="<?php echo asset('common/css/validation.css');?>">

    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/jquery.validate.min.js"></script>
    <script src="<?php echo asset('common/js/messages_fr.js');?>"></script>
    <script src="<?php echo asset('common/js/validation.js');?>"></script>
</head>
<body class="focusedform">
    <div class="{{ !Request::is('auth/register') ? 'verticalcenter' : 'verticalcenterregister' }}">

        <!-- messages and errors -->
        @include('backend.partials.message')

        <div class="panel panel-inverse">

            <h1 class="auth-logo text-center">
                <a class="text-inverse" href="{{ url('/') }}">
                    @if(session()->has('admin'))
                        Droit Formation | <span class="text-danger">Administration</span>
                    @else
                        <img style="height: 75px; width:380px;" src="{{ asset('frontend/pubdroit/images/logo.svg') }}" />
                    @endif
                </a>
            </h1>

            <p><a href="{{ url('/') }}" class="text-danger"><i class="fa fa-arrow-circle-left"></i> &nbsp;Retour au site</a></p>

            <!-- Contenu -->
            @yield('content')
            <!-- Fin contenu -->

        </div>

    </div>
</body>
</html>
