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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
          integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

    <!-- Local -->
    <link rel="stylesheet" type="text/css" href="<?php echo asset('common/css/chosen.css');?>">
    <link rel="stylesheet" href="<?php echo asset('frontend/common/css/structure.css');?>">
    <link rel="stylesheet" href="<?php echo asset('frontend/bail/css/main.css');?>">
    <link rel="stylesheet" href="<?php echo asset('frontend/common/css/filter.css');?>">

    <!-- Javascript Files
    ================================================== -->
    <!-- CDN -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/jquery.validate.min.js"></script>

    <!-- Local -->
    <script src="<?php echo asset('common/js/messages_fr.js');?>"></script>
    <script src="<?php echo asset('common/js/chosen.jquery.js');?>"></script>

	</head>
	<body>
        <div id="main" class="container">

            <div class="row" id="content-wrapper">
                <!-- Contenu principal -->
                <div id="mainContent" class="maincontent col-md-9">

                    <!-- Entête et menu -->
                    <header class="header">
                        <div class="row">
                            <h1 class="col-md-3"><a class="" href="{{ url('bail/page/index') }}"><img src="{{ asset('/images/bail/logo.png') }}" alt="Logo Bail.ch"></a></h1>
                            <nav class="col-md-9" id="menu-principal">

                                @if(!$menus->isEmpty())
                                    <?php $menu = $menus->where('position','main'); ?>
                                    @if(!$menu->isEmpty())
                                        <?php $menu = $menu->first()->load('pages'); ?>
                                        @if(!$menu->pages->isEmpty())
                                            @foreach($menu->pages as $page)
                                                <a class="{{ Request::is('bail/page/'.$page->slug) ? 'active' : '' }}" href="{{ url('bail/page/'.$page->slug) }}">{{ $page->menu_title }}</a>
                                            @endforeach
                                        @endif
                                    @endif
                                @endif

                            </nav>
                        </div>
                    </header>

                    <!-- Fil d'ariane -->
                    <section id="breadcrumbs" class="colorBlock min-inner colorSection"></section>

                    <!-- Contenu -->

                    @yield('content')

                    <!-- Fin contenu -->

                </div>
                <!-- Fin contenu principal -->

                <!-- sidebar -->
                @include('frontend.bail.partials.sidebar')
                <!-- End sidebar -->

            </div>

            <footer id="mainFooter" class="colorBlock">
                © {{ date('Y') }} - bail.ch<br/>
                Université de Neuchâtel, Faculté de droit, Av. du 1er mars 26, 2000 Neuchâtel<br/>
                <a href="mailto:seminaire.bail@unine.ch">seminaire.bail@unine.ch</a>
            </footer>

	    </div>


        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"
                integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

        <script src="<?php echo asset('frontend/common/js/sidebar.js');?>"></script>
        <script src="<?php echo asset('frontend/common/js/arrets.js');?>"></script>
        <script src="<?php echo asset('frontend/bail/js/seminaires.js');?>"></script>
        <script src="<?php echo asset('frontend/bail/js/calculette.js');?>"></script>
        <script src="<?php echo asset('frontend/bail/js/main.js');?>"></script>

	</body>
</html>