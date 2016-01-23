<!DOCTYPE html>
<head>
    <meta charset="utf-8" />
	<title>Bail</title>
	
	<meta name="description" content="Bail">
	<meta name="author" content="Cindy Leschaud | @DesignPond">

    <!-- CSS Files
    ================================================== -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	<link rel="stylesheet" href="<?php echo asset('css/jquery-ui.min.css'); ?>" type="text/css"  />
	<link rel="stylesheet" type="text/css" href="<?php echo asset('frontend/css/chosen.css');?>">
	<link rel="stylesheet" href="<?php echo asset('css/bail/main.css');?>">
    <link rel="stylesheet" href="<?php echo asset('frontend/css/filter.css');?>">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Javascript Files
    ================================================== -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
	<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
	<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/jquery.validate.min.js"></script>
	<script src="<?php echo asset('backend/js/validation/messages_fr.js');?>"></script>
	<script type="text/javascript" src="<?php echo asset('frontend/js/chosen.jquery.js');?>"></script>
    <script src="<?php echo asset('js/bail/main.js');?>"></script>
    <script src="<?php echo asset('js/bail/bail.js');?>"></script>
    <script src="<?php echo asset('js/bail/arrets.js');?>"></script>
    <script src="<?php echo asset('js/bail/seminaires.js');?>"></script>

	</head>
	<body>
        <div id="main" class="container">
            <div class="row">
                <!-- Contenu principal -->
                <div class="maincontent col-md-9">

                    <!-- Entête et menu -->
                    <header class="header">
                        <div class="row">
                            <h1 class="col-md-3"><a class="" href="{{ url('bail/page/home') }}"><img src="{{ asset('/images/bail/logo.png') }}" alt="Logo Bail.ch"></a></h1>
                            <nav class="col-md-9" id="menu-principal">

                                @if(!$menus->isEmpty())
                                    <?php $menu = $menus->whereLoose('position','main'); ?>
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
                    <section id="breadcrumbs" class="colorBlock min-inner colorSection">Home <a href=""> &gt; Newsletter</a></section>

                    <!-- Contenu -->

                    @yield('content')

                    <!-- Fin contenu -->

                    <footer class="colorBlock">
                        © 2013 - bail.ch<br/>
                        Université de Neuchâtel, Faculté de droit, Av. du 1er mars 26, 2000 Neuchâtel<br/>
                        <a href="mailto:seminaire.bail@unine.ch">seminaire.bail@unine.ch</a>
                    </footer>

                </div>
                <!-- Fin contenu principal -->

                @include('frontend.bail.partials.sidebar')

            </div>
	    </div>
    	
	</body>
</html>