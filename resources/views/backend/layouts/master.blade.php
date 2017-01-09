<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Administration | Droit Formation</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Administration | Droit Formation">
    <meta name="author" content="Cindy Leschaud | @DesignPond">
    <meta name="_token" content="<?php echo csrf_token(); ?>">

    <script src="https://use.fontawesome.com/fd16a07224.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

    <link rel="stylesheet" type="text/css" href="<?php echo asset('backend/css/styles.css?=121');?>">
    <link rel="stylesheet" type="text/css" href="<?php echo asset('backend/css/responsive.css');?>">
    <link rel="stylesheet" type="text/css" href="<?php echo asset('backend/css/dropzone.css?=13321');?>">
    <link rel="stylesheet" type="text/css" href="<?php echo asset('backend/css/files.css?=1321');?>">
    <link rel="stylesheet" type="text/css" href="<?php echo asset('common/css/chosen.css');?>">
    <link rel="stylesheet" type="text/css" href="<?php echo asset('common/css/chosen-bootstrap.css');?>">
    <link rel="stylesheet" type="text/css" href="<?php echo asset('backend/css/jquery.tagit.css');?>">

    <link rel='stylesheet' type='text/css' href="//cdn.datatables.net/plug-ins/f2c75b7247b/integration/bootstrap/3/dataTables.bootstrap.css" />
    <link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="<?php echo asset('backend/css/admin.css');?>">
    <link rel="stylesheet" type="text/css" href="<?php echo asset('backend/css/inscription.css');?>" media="screen" />
    <link rel="stylesheet" type="text/css" href="<?php echo asset('backend/css/types.css');?>">
    <link rel="stylesheet" type="text/css" href="<?php echo asset('backend/js/redactor/redactor.css');?>">

    <link rel='stylesheet' type='text/css' href="<?php echo asset('backend/plugins/form-multiselect/css/multi-select.css');?>" />
    <link rel='stylesheet' type='text/css' href="<?php echo asset('backend/plugins/form-nestable/jquery.nestable.css');?>" />

    @if(isset($isNewsletter))
        @include('newsletter::Style.main', ['campagne' => isset($campagne) ? : null])
    @endif

    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
    <base href="/">

    <script>
        window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
                'url'   => url('/'),
                'ajax' => url('admin/ajax/')
        ]); ?>
    </script>
</head>
<body>

<?php $current_user = (isset(Auth::user()->name) ? Auth::user()->name : ''); ?>

<header class="navbar navbar-inverse navbar-fixed-top" role="banner">

    <a id="leftmenu-trigger" class="tooltips" data-toggle="tooltip" data-placement="right" title="Toggle Sidebar"></a>
    <div class="navbar-header pull-left"><a class="navbar-brand" href="{{ url('/')  }}">Droit Formation</a></div>

    <ul class="nav navbar-nav pull-right toolbar">
        <li class="dropdown">
            <a href="#" class="dropdown-toggle tooltips" data-toggle="dropdown">{{ $current_user }}</a>
            <ul class="dropdown-menu userinfo arrow">
                <li class="userlinks">
                    <ul class="dropdown-menu">
                        <li>
                            <form class="logout" action="{{ url('logout') }}" method="POST">{{ csrf_field() }}
                                <button class="btn btn-default btn-xs" type="submit"><i class="fa fa-power-off" aria-hidden="true"></i> &nbsp;Logout</button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </li>
        <!-- Quick links -->
        @include('backend.partials.sites')
    </ul>
</header>

<div id="page-container">

    <!-- Navigation  -->
    @include('backend.partials.navigation')

    <div id="page-content">
        <div id='wrap'>

            <!-- messages and errors -->
            @include('alert::alert')
            @include('backend.partials.message')

            <div id="page-heading">
                @if(isset($sites) && isset($current_site) && $sites->contains('id',$current_site))
                    <div class="site-bar color-{{ $sites->find($current_site)->slug }}">
                        {{ $sites->find($current_site)->nom }}
                    </div>
                @else
                    <h2>{!! $pageTitle or 'Droit Formation <small>Administration</small>' !!}</h2>
                @endif
            </div>

            <div class="container" id="mainContainer">

                <!-- Contenu -->
                @yield('content')
                <!-- Fin contenu -->

            </div> <!-- container -->
        </div> <!--wrap -->
    </div> <!-- page-content -->

    <footer role="contentinfo">
        <div class="clearfix">
            <ul class="list-unstyled list-inline pull-left">
                <li>Droit Formation &copy; <?php echo date('Y'); ?></li>
            </ul>
            <button class="pull-right btn btn-inverse-alt btn-xs hidden-print" id="back-to-top"><i class="fa fa-arrow-up"></i></button>
        </div>
    </footer>

</div> <!-- page-container -->

<script src="http://code.jquery.com/jquery-migrate-1.0.0.js"></script>
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/r/bs/dt-1.10.9/datatables.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
<script src="<?php echo asset('backend/js/vendor/bootstrap/bootstrap-editable.js');?>"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

<!-- Filter plugin -->
<script type="text/javascript" src="<?php echo asset('common/js/chosen.jquery.js');?>"></script>
<script type="text/javascript" src="<?php echo asset('backend/js/vendor/jquery/tag-it.min.js');?>"></script>

<!-- Layout and fixes plugins -->
<script type="text/javascript" src="<?php echo asset('backend/js/layouts/enquire.js');?>"></script>
<script type="text/javascript" src="<?php echo asset('backend/js/vendor/jquery/jquery.cookie.js');?>"></script>
<script type="text/javascript" src="<?php echo asset('backend/js/vendor/jquery/jquery.nicescroll.min.js');?>"></script>
<script type="text/javascript" src="<?php echo asset('backend/js/layouts/application.js');?>"></script>

@include('backend.scripts.redactor')

<!-- Form plugins -->
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/jquery.validate.min.js"></script>
<script src="<?php echo asset('common/js/messages_fr.js');?>"></script>
<script type="text/javascript" src="<?php echo asset('backend/js/vendor/libraries/placeholdr.js');?>"></script>
<script type='text/javascript' src="<?php echo asset('backend/plugins/form-multiselect/js/jquery.multi-select.js');?>"></script>
<script type='text/javascript' src="<?php echo asset('backend/plugins/form-multiselect/js/jquery.quicksearch.js');?>"></script>
<script type='text/javascript' src="<?php echo asset('backend/plugins/form-datepicker/js/bootstrap-datepicker.js');?>"></script>
<script type='text/javascript' src="<?php echo asset('backend/plugins/bootbox/bootbox.min.js');?>"></script>

<!-- Upload plugins -->
<script type="text/javascript" src="<?php echo asset('backend/js/upload/dropzone.js');?>"></script>

<!-- Scripts -->
<script type="text/javascript" src="<?php echo asset('backend/js/datatables.js');?>"></script>

@include('newsletter::Script.config')

@if(isset($isNewsletter))
    @include('newsletter::Script.date')
    @include('newsletter::Script.angular')
    @include('newsletter::Script.main')
@endif

<script type="text/javascript" src="<?php echo asset('backend/js/select-list.js');?>"></script>
<script type="text/javascript" src="<?php echo asset('backend/js/admin.js');?>"></script>
<script type="text/javascript" src="<?php echo asset('backend/js/search-user.js');?>"></script>
<script type="text/javascript" src="<?php echo asset('backend/js/search-adresse.js');?>"></script>
<script type="text/javascript" src="<?php echo asset('backend/js/order.js');?>"></script>
<script type="text/javascript" src="<?php echo asset('backend/js/inscription.js');?>"></script>
<script type="text/javascript" src="<?php echo asset('backend/js/upload/upload.js');?>"></script>
<script type="text/javascript" src="<?php echo asset('backend/js/specialisation.js');?>"></script>
<script type="text/javascript" src="<?php echo asset('backend/js/colloque.js');?>"></script>
<script type="text/javascript" src="<?php echo asset('backend/js/rappels.js');?>"></script>
<script type="text/javascript" src="<?php echo asset('backend/js/abo.js');?>"></script>
<script type="text/javascript" src="<?php echo asset('backend/js/sorting.js');?>"></script>
<script type="text/javascript" src="<?php echo asset('backend/js/member.js');?>"></script>
<script type="text/javascript" src="<?php echo asset('backend/js/manager/manager.js');?>"></script>
<script type="text/javascript" src="<?php echo asset('backend/js/tree.js');?>"></script>
<script type="text/javascript" src="<?php echo asset('backend/js/blocs.js');?>"></script>
<script type="text/javascript" src="<?php echo asset('backend/js/sondage.js');?>"></script>

<script type="text/javascript" src="{{ asset('js/app.js') }}"></script>

</body>
</html>