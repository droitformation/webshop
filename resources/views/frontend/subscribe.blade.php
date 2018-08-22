<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="@Designpond | Cindy Leschaud">
    <title>Droit Formation | Inscription</title>
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
    @include('alert::bootstrap')

    <div class="panel panel-inverse">

        <h1 class="auth-logo text-center">
            <a class="text-inverse" href="{{ $site->url }}">
                <img style="height: 75px; width:380px;" src="{{ secure_asset('logos/'. $site->logo) }}" />
            </a>
        </h1>

        <p><a href="{{ $site->url }}" target="_parent" class="text-inverse"><i class="fa fa-arrow-circle-left"></i> &nbsp;Retour au site</a></p>

        <div class="well" style="background: #fff;">
          <h3>{{ $action }} à la newsletter</h3>
          <h4>{{ $newsletter->titre }}</h4>
          <form action="{{ url($url) }}" method="POST" class="form" id="subscribe">{!! csrf_field() !!}
              <div class="form-group">
                  <label class="control-label">Votre email</label>
                  <div class="input-group">
                      <input type="text" class="form-control" name="email" value="{{ old('email') or '' }}">
                      <span class="input-group-btn">
                    <button class="btn btn-default" type="submit">{{ $action }}</button>
                </span>
                  </div>
              </div>
              <input type="hidden" name="newsletter_id" value="{{ $newsletter->id }}">
              <input type="hidden" name="site_id" value="{{ $site->id }}">
              <input type="hidden" name="return_path" value="{{ $site->url }}">
              {!! Honeypot::generate('my_name', 'my_time') !!}
          </form>
        </div>
    </div>
  </div>
  </body>
  </html>
