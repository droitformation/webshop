<!DOCTYPE html>
<head>
    <meta charset="utf-8" />
    <title>Bail</title>

    <meta name="description" content="Bail">
    <meta name="author" content="Cindy Leschaud | @DesignPond">
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
    <link rel="stylesheet" href="<?php echo secure_asset('frontend/bail/css/main.css');?>">
    <link rel="stylesheet" href="<?php echo secure_asset('frontend/common/css/filter.css');?>">
    <link rel="stylesheet" href="<?php echo secure_asset('common/css/sites.css');?>">
    <link rel="stylesheet" href="<?php echo secure_asset('frontend/bail/css/responsive.css');?>">

    <!-- Javascript Files
    ================================================== -->
    <!-- CDN -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
    <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/jquery.validate.min.js"></script>

    <!-- Local -->
    <script src="<?php echo secure_asset('common/js/messages_fr.js');?>"></script>
    <script src="<?php echo secure_asset('common/js/chosen.jquery.js');?>"></script>

    <script>
        window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token()
        ]); ?>
    </script>

</head>
<body>
<div id="main" class="container">

    <!-- Entête et menu -->
    <header class="header" id="app">
        <div class="row">
            <h1 class="col-md-3 col-xs-12">
                <a class="" href="{{ url('bail/page/index') }}"><img src="{{ secure_asset('/logos/bail.svg') }}" alt="Logo Bail.ch"></a>
            </h1>
            <div class="col-md-7 col-xs-12 text-right">
                <nav id="menu-principal">
                    @if(isset($menu_main))
                        @if(!$menu_main->pages_active->isEmpty())
                            @foreach($menu_main->pages_active as $page)
                                <a class="{{ Request::is('bail/page/'.$page->slug) ? 'active' : '' }}" href="{{ url('bail/page/'.$page->slug) }}">{{ $page->menu_title }}</a>
                            @endforeach
                        @endif
                    @endif
                </nav>
            </div>
            <div class="col-md-2 col-xs-12">
                <!--Logo unine -->
                <div class="sidebar-unine-logo">
                    <p class="text-right ">
                        <a href="//www.unine.ch" target="_blank">
                            <img src="{{ secure_asset('/images/bail/unine.png') }}" alt="">
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </header>

    <div class="row" id="content-wrapper">
        <!-- Contenu principal -->
        <div id="mainContent" class="maincontent">
            <section class="colorBlock min-inner colorSection headerBreadcrumb"></section>

            @include('partials.message')
            @include('alert::bootstrap')

            <!-- Contenu -->
            @yield('content')
            <!-- Fin contenu -->

        </div>
    </div>
    <footer id="mainFooter" class="colorBlock">
        © {{ date('Y') }} - bail.ch<br/>
        Université de Neuchâtel, Faculté de droit, Av. du 1er mars 26, 2000 Neuchâtel<br/>
        <a href="mailto:seminaire.bail@unine.ch">seminaire.bail@unine.ch</a>
    </footer>

    @include('partials.logos', ['class' => 'bail'])
</div>

<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"
        integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

<script src="<?php echo secure_asset('frontend/common/js/sidebar.js');?>"></script>
<script src="<?php echo secure_asset('frontend/common/js/arrets.js');?>"></script>
<script src="<?php echo secure_asset('frontend/bail/js/seminaires.js');?>"></script>
<script src="<?php echo secure_asset('frontend/bail/js/main.js');?>"></script>
<script src="<?php echo secure_asset('common/js/chosen.jquery.js');?>"></script>
<script src="<?php echo secure_asset('js/app.js');?>"></script>

</body>
</html>