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

    <link href='//fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="<?php echo secure_asset('backend/css/styles.css?='.rand(432,123456).'');?>">
    <link rel="stylesheet" type="text/css" href="<?php echo secure_asset('common/css/validation.css');?>">

    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
    <script src="//use.fontawesome.com/037c712a00.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/jquery.validate.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo secure_asset('frontend/pubdroit/css/sweetalert.css');?>">
    <script src="<?php echo secure_asset('common/js/messages_fr.js');?>"></script>
    <script src="<?php echo secure_asset('common/js/validation.js');?>"></script>

</head>
<body class="">
    <div class="{{ !Request::is('register') ? 'verticalcenter' : 'verticalcenterregister' }}">

        <!-- messages and errors -->
        @include('backend.partials.message')
        @include('partials.confirmation')

        <div class="panel panel-inverse">

            <h1 class="auth-logo text-center">
                <a class="text-inverse" href="{{ url('/') }}">
                    @if(session()->has('admin'))
                        Droit Formation | <span class="text-danger">Administration</span>
                    @else
                        <img style="height: 75px; width:380px;" src="{{ secure_asset('frontend/pubdroit/images/logo.svg') }}" />
                    @endif
                </a>
            </h1>

            <p>
                <a href="{{ url('/') }}" class="text-danger"><i class="fa fa-arrow-circle-left"></i> &nbsp;Retour au site</a>
                @if(Request::is('register'))
                    <a href="{{ url('login') }}" class="btn btn-primary pull-right"><i class="fa fa-user"></i> &nbsp;Login</a>
                @endif
            </p><br/>

            <!-- Contenu -->
            @yield('content')
            <!-- Fin contenu -->

        </div>

    </div>
    <script src="<?php echo secure_asset('frontend/pubdroit/js/sweetalert.min.js');?>"></script>
</body>
</html>
