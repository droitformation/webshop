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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
          integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

    <!-- Local -->
    <link rel="stylesheet" type="text/css" href="<?php echo asset('common/css/chosen.css');?>">
    <link rel="stylesheet" href="<?php echo asset('frontend/common/css/structure.css');?>">
    <link rel="stylesheet" href="<?php echo asset('frontend/matrimonial/css/main.css');?>">
    <link rel="stylesheet" href="<?php echo asset('frontend/common/css/filter.css');?>">

    <!-- Javascript Files
     ================================================== -->
    <!-- CDN -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/jquery.validate.min.js"></script>
    <script src="https://unpkg.com/bootstrap-vue/dist/bootstrapVue.js"></script>

    <!-- Local -->
    <script src="<?php echo asset('common/js/messages_fr.js');?>"></script>
    <script src="<?php echo asset('common/js/chosen.jquery.js');?>"></script>

    <script>
        window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token()
        ]); ?>
    </script>

</head>
<body>
<div id="main" class="container">

    <header class="header" id="app">
        <div class="row">
            <h1 class="col-md-4">
                <a class="" href="{{ url('matrimonial/page/index') }}">
                    <img src="{{ asset('logos/droitmatrimonial.svg') }}" alt="Logo droitmatrimonial.ch">
                </a>
            </h1>
            <div class="col-md-6 text-right">
                <nav id="menu-principal" class="menu-principal-app">
                    @if(isset($menu_main))
                        @if(!$menu_main->pages->isEmpty())
                            @foreach($menu_main->pages as $page)
                                <a class="{{ Request::is('bail/page/'.$page->slug) ? 'active' : '' }}" href="{{ url('matrimonial/page/'.$page->slug) }}">{{ $page->menu_title }}</a>
                            @endforeach
                        @endif
                    @endif
                </nav>
            </div>
            <div class="col-md-2">
                @include('frontend.matrimonial.sidebar.logo')
            </div>
        </div>
    </header>
    <div class="row" id="content-wrapper">
        <!-- Contenu principal -->
        <div id="mainContent" class="maincontent">

            <!-- Contenu -->
            @yield('content')
            <!-- Fin contenu -->

        </div>

    </div>
    <footer id="mainFooter" class="colorBlock">
        © {{ date('Y') }} - droitmatrimonial.ch<br/>
        Université de Neuchâtel, Faculté de droit, Av. du 1er mars 26, 2000 Neuchâtel<br/>
        <a href="mailto:droit.matrimonial@unine.ch">droit.matrimonial@unine.ch</a>
    </footer>
</div>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"
        integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

<script src="<?php echo asset('frontend/common/js/sidebar.js');?>"></script>
<script src="<?php echo asset('frontend/common/js/arrets.js');?>"></script>
<script src="<?php echo asset('common/js/chose.jquery.js');?>"></script>
<script src="<?php echo asset('js/app.js');?>"></script>

</body>
</html>