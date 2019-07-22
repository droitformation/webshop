<!DOCTYPE html>
<head>
    <meta charset="utf-8" />
    <title>Matrimonial</title>

    <meta name="description" content="Droit matrimonial">
    <meta name="author" content="Cindy Leschaud | @DesignPond">
    <meta name="viewport" content="width=device-width">
    <meta name="_token" content="<?php echo csrf_token(); ?>">

    <!-- CSS Files
     ================================================== -->
    <!-- CDN -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
          integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

    <!-- Local -->
    <link rel="stylesheet" type="text/css" href="<?php echo secure_asset('common/css/chosen.css');?>">
    <link rel="stylesheet" href="<?php echo secure_asset('frontend/common/css/structure.css');?>">
    <link rel="stylesheet" href="<?php echo secure_asset('frontend/matrimonial/css/main.css');?>">
    <link rel="stylesheet" href="<?php echo secure_asset('frontend/common/css/filter.css');?>">
    <link rel="stylesheet" href="<?php echo secure_asset('common/css/sites.css');?>">
    <link rel="stylesheet" href="<?php echo secure_asset('frontend/matrimonial/css/responsive.css');?>">
    <link rel="stylesheet" href="<?php echo secure_asset('common/css/jquery.fancybox.min.css');?>">

    <!-- Javascript Files
     ================================================== -->
    <!-- CDN -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
    <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/jquery.validate.min.js"></script>

    <!-- Local -->
    <script src="<?php echo secure_asset('common/js/messages_fr.js');?>"></script>
    <script src="<?php echo secure_asset('common/js/chosen.jquery.js');?>"></script>
    <script src="<?php echo secure_asset('common/js/jquery.fancybox.min.js');?>"></script>
    <script src="<?php echo secure_asset('common/js/common.js');?>"></script>

    <script>
        window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token()
        ]); ?>
    </script>

</head>
<body>

@if(config('app.env') == 'staging' || config('app.env') == 'local')
    <div class="alert alert-warning text-center" role="alert">Mode test</div>
@endif

<div class="container">
    <div id="main">

        <div id="content-wrapper">
            <header class="header">
                <div class="row">
                    <h1 class="col-md-4 col-xs-12">
                        <a class="" href="{{ url('matrimonial/page/index') }}">
                            <img height="65px" src="{{ secure_asset('logos/droitmatrimonial.svg') }}" alt="Logo droitmatrimonial.ch">
                        </a>
                    </h1>
                    <div class="col-md-6 col-xs-12 text-right">
                        <nav id="menu-principal" class="menu-principal-app">
                            @if(isset($menu_main))
                                @if(!$menu_main->active->isEmpty())
                                    @foreach($menu_main->active as $page)
                                        <a class="{{ Request::is('bail/page/'.$page->slug) ? 'active' : '' }}" href="{{ url('matrimonial/page/'.$page->slug) }}">{{ $page->menu_title }}</a>
                                    @endforeach
                                @endif
                            @endif
                        </nav>
                    </div>
                    <div class="col-md-2  col-xs-12">
                        @include('frontend.matrimonial.sidebar.logo')
                    </div>
                </div>
            </header>

            <!-- Contenu principal -->
            <div id="mainContent" class="maincontent">

                @include('partials.message')
                @include('alert::bootstrap')

                <!-- Contenu -->
                @yield('content')
                <!-- Fin contenu -->

            </div>

            <footer id="mainFooter" class="colorBlock">
                © {{ date('Y') }} - droitmatrimonial.ch<br/>
                Université de Neuchâtel, Faculté de droit, Av. du 1er mars 26, 2000 Neuchâtel<br/>
                <a href="mailto:droit.matrimonial@unine.ch">droit.matrimonial@unine.ch</a>
            </footer>

            @include('partials.logos', ['class' => 'matrimonial'])
        </div>

    </div>
</div>

<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"
        integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

<script src="<?php echo secure_asset('frontend/common/js/sidebar.js');?>"></script>
<script src="<?php echo secure_asset('frontend/common/js/arrets.js');?>"></script>

<script src="<?php echo secure_asset('js/app.js');?>"></script>

</body>
</html>