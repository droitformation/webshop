<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="@Designpond | Cindy Leschaud">
    <title>Droit Formation | Confirmation Newsletter</title>
    <meta name="description" content="Inscription">
    <meta name="_token" content="<?php echo csrf_token(); ?>">

    <link href='//fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="<?php echo secure_asset('backend/css/styles.css');?>">
    <script src="//use.fontawesome.com/037c712a00.js"></script>


</head>
<body>
    <div class="verticalcenter">

        <!-- messages and errors -->
        @include('backend.partials.message')
        @include('flash::message')

        <div class="panel panel-inverse">

            <h1 class="auth-logo text-center">
                <a class="text-inverse" href="{{ $site->url }}">
                    <img style="height: 75px; width:380px;" src="{{ secure_asset('logos/'. $site->logo) }}" />
                </a>
            </h1>

            <h2>Vous êtes maintenant abonné à la newsletter <strong>{{ $newsletter->titre }}</strong></h2><br/>
            <p><a href="{{ $site->url }}" class="text-inverse"><i class="fa fa-arrow-circle-left"></i> &nbsp;Retour au site</a></p>

        </div>

      </div>
  </body>
  </html>
