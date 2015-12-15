<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Administration | HUB Droit Formation</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Administration | HUB Droit Formation">
    <meta name="author" content="Cindy Leschaud | @DesignPond">
    <meta name="_token" content="<?php echo csrf_token(); ?>">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo asset('backend/css/styles.css?=121');?>">
    <link rel="stylesheet" type="text/css" href="<?php echo asset('backend/css/files.css?=1321');?>">
    <link rel="stylesheet" type="text/css" href="<?php echo asset('backend/css/dropzone.css?=13321');?>">

    @if(isset($isNewsletter))
        <link rel="stylesheet" href="<?php echo asset('newsletter/css/backend/newsletter.css'); ?>">
        <link rel="stylesheet" href="<?php echo asset('newsletter/css/frontend/newsletter.css'); ?>">

        @if(isset($infos))
            <style type="text/css">
                #StyleNewsletter h2, #StyleNewsletterCreate h2{
                    color: {{ $infos->newsletter->color }};
                }
                #StyleNewsletter .contentForm h3,
                #StyleNewsletter .contentForm h4,
                #StyleNewsletterCreate .contentForm h3,
                #StyleNewsletterCreate .contentForm h4
                {
                    color: {{ $infos->newsletter->color }};
                }
            </style>
        @endif

    @endif


    <link rel="stylesheet" type="text/css" href="<?php echo asset('backend/js/vendor/redactor/redactor.css'); ?>">
    <link rel='stylesheet' type='text/css' href="//cdn.datatables.net/plug-ins/f2c75b7247b/integration/bootstrap/3/dataTables.bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo asset('backend/css/chosen.css');?>">
    <link rel="stylesheet" type="text/css" href="<?php echo asset('backend/css/chosen-bootstrap.css');?>">
    <link rel="stylesheet" type="text/css" href="<?php echo asset('backend/js/dragdrop/dragula.min.css');?>">
    <link rel="stylesheet" type="text/css" href="<?php echo asset('backend/css/jquery.tagit.css');?>">

    <link rel='stylesheet' type='text/css' href="<?php echo asset('backend/plugins/form-multiselect/css/multi-select.css');?>" />
    <link rel='stylesheet' type='text/css' href="<?php echo asset('backend/plugins/form-nestable/jquery.nestable.css');?>" />

    <link rel="stylesheet" type="text/css" href="<?php echo asset('backend/css/admin.css');?>">
    <link rel="stylesheet" type="text/css" href="<?php echo asset('backend/css/dragdrop.css');?>">
    <link rel="stylesheet" type="text/css" href="<?php echo asset('css/colloque/inscription.css');?>" media="screen" />
    <link rel="stylesheet" type="text/css" href="<?php echo asset('backend/css/types.css');?>">

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <base href="/">

</head>
<body>

<?php $current_user = (isset(Auth::user()->name) ? Auth::user()->name : ''); ?>

<header class="navbar navbar-inverse navbar-fixed-top" role="banner">

    <a id="leftmenu-trigger" class="tooltips" data-toggle="tooltip" data-placement="right" title="Toggle Sidebar"></a>
    <div class="navbar-header pull-left"><a class="navbar-brand" href="{{ url('/')  }}">HUB Droit Formation</a></div>

    <ul class="nav navbar-nav pull-right toolbar">
        <li class="dropdown">
            <a href="#" class="dropdown-toggle username" data-toggle="dropdown">
               <span class="hidden-xs">&nbsp;{{ $current_user }}<i class="fa fa-caret-down"></i></span>
            </a>
            <ul class="dropdown-menu userinfo arrow">
                <li class="username">
                    <a href="#"><div class="pull-right"><h5>Bonjour, {{ $current_user }}!</h5></div></a>
                </li>
                <li class="userlinks">
                    <ul class="dropdown-menu">
                        <li><a href="{{ url('logout') }}"><i class="pull-right fa  fa-power-off"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
        </li>
    </ul>
</header>

<div id="page-container">

    <!-- Navigation  -->
    @include('backend.partials.navigation')

    <div id="page-content">
        <div id='wrap'>

            <div id="page-heading"><h2>{{ $pageTitle or 'HUB Droit Formation' }} <small>Administration</small></h2></div>

            <div class="container">

                <!-- messages and errors -->
                @include('backend.partials.message')

                <!-- Contenu -->
                @yield('content')
                <!-- Fin contenu -->

            </div> <!-- container -->
        </div> <!--wrap -->
    </div> <!-- page-content -->

    <footer role="contentinfo">
        <div class="clearfix">
            <ul class="list-unstyled list-inline pull-left">
                <li>HUB Droit Formation &copy; <?php echo date('Y'); ?></li>
            </ul>
            <button class="pull-right btn btn-inverse-alt btn-xs hidden-print" id="back-to-top"><i class="fa fa-arrow-up"></i></button>
        </div>
    </footer>

</div> <!-- page-container -->

<script src="http://code.jquery.com/jquery-migrate-1.0.0.js"></script>
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/r/bs/dt-1.10.9/datatables.min.js"></script>

<!-- Filter plugin -->
<script type="text/javascript" src="<?php echo asset('backend/js/vendor/chosen/chosen.jquery.js');?>"></script>
<script type="text/javascript" src="<?php echo asset('backend/js/vendor/jquery/tag-it.min.js');?>"></script>

<!-- Layout and fixes plugins -->
<script type="text/javascript" src="<?php echo asset('backend/js/layouts/enquire.js');?>"></script>
<script type="text/javascript" src="<?php echo asset('backend/js/vendor/jquery/jquery.cookie.js');?>"></script>
<script type="text/javascript" src="<?php echo asset('backend/js/vendor/jquery/jquery.nicescroll.min.js');?>"></script>
<script type="text/javascript" src="<?php echo asset('backend/js/layouts/application.js');?>"></script>

<!-- redactor -->
<script type="text/javascript" src="<?php echo asset('backend/js/vendor/redactor/redactor.js');?>"></script>
<script type="text/javascript" src="<?php echo asset('backend/js/vendor/redactor/fr.js');?>"></script>
<script type="text/javascript" src="<?php echo asset('backend/js/vendor/redactor/imagemanager.js');?>"></script>
<script type="text/javascript" src="<?php echo asset('backend/js/vendor/redactor/filemanager.js');?>"></script>

<!-- Form plugins -->
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/jquery.validate.min.js"></script>
<script src="<?php echo asset('backend/js/validation/messages_fr.js');?>"></script>
<script type="text/javascript" src="<?php echo asset('backend/js/vendor/libraries/placeholdr.js');?>"></script>
<script type='text/javascript' src="<?php echo asset('backend/plugins/form-multiselect/js/jquery.multi-select.js');?>"></script>
<script type='text/javascript' src="<?php echo asset('backend/plugins/form-multiselect/js/jquery.quicksearch.js');?>"></script>
<script type='text/javascript' src="<?php echo asset('backend/plugins/form-datepicker/js/bootstrap-datepicker.js');?>"></script>
<script type='text/javascript' src="<?php echo asset('backend/js/vendor/jquery/jqColorPicker.min.js');?>"></script>
<script type='text/javascript' src="<?php echo asset('backend/js/vendor/jquery/jquery.ddslick.min.js');?>"></script>
<script type='text/javascript' src="<?php echo asset('backend/plugins/form-datepicker/js/bootstrap-datepicker.js');?>"></script>
<script type='text/javascript' src="<?php echo asset('backend/plugins/bootbox/bootbox.min.js');?>"></script>

<!-- Upload plugins -->
<script type="text/javascript" src="<?php echo asset('backend/js/upload/dropzone.js');?>"></script>

<!-- Gallery plugins -->
<script type='text/javascript' src="<?php echo asset('backend/plugins/mixitup/jquery.mixitup.min.js');?>"></script>
<script type='text/javascript' src="<?php echo asset('backend/js/gallery/gallery-simple.js');?>"></script>

<!-- Scripts -->
<script type="text/javascript" src="<?php echo asset('backend/js/datatables.js');?>"></script>
<script src="<?php echo asset('js/colloque/inscription.js');?>"></script>

<script type='text/javascript' src="<?php echo asset('backend/js/dragdrop/dragula.js');?>"></script>
<script type='text/javascript' src="<?php echo asset('backend/js/dragdrop/dragdrop.js');?>"></script>
<script type="text/javascript" src="<?php echo asset('backend/js/admin.js');?>"></script>
<script type="text/javascript" src="<?php echo asset('backend/js/inscription.js');?>"></script>
<script type="text/javascript" src="<?php echo asset('backend/js/user.js');?>"></script>
<script type="text/javascript" src="<?php echo asset('backend/js/angular/angular-flow.js');?>"></script>
<script type="text/javascript" src="<?php echo asset('backend/js/upload/upload.js');?>"></script>
<script type="text/javascript" src="<?php echo asset('backend/js/specialisation.js');?>"></script>
<script type="text/javascript" src="<?php echo asset('backend/js/member.js');?>"></script>
<script type="text/javascript" src="<?php echo asset('backend/js/upload/file-upload.js');?>"></script>
<script type="text/javascript" src="<?php echo asset('backend/js/tree.js');?>"></script>

@if(isset($isNewsletter))
    @include('backend.newsletter.scripts')
@endif



</body>
</html>