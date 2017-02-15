<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Administration | Droit Formation | Team</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Administration | Droit Formation">
    <meta name="author" content="Cindy Leschaud | @DesignPond">
    <meta name="_token" content="<?php echo csrf_token(); ?>">

    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.6.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="<?php echo asset('backend/css/styles.css?=121');?>">
    <link rel="stylesheet" type="text/css" href="<?php echo asset('backend/css/responsive.css');?>">
    <link rel='stylesheet' type='text/css' href="//cdn.datatables.net/plug-ins/f2c75b7247b/integration/bootstrap/3/dataTables.bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo asset('backend/css/admin.css');?>">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
    <base href="/">

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
                        <li><a href="{{ url('logout') }}"><i class="pull-right fa  fa-power-off"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
        </li>
    </ul>
</header>

<div id="page-container">

    <!-- Navigation  -->
    @include('team.partials.navigation')

    <div id="page-content">
        <div id='wrap'>

            <div id="page-heading">
                <h2>Droit Formation <small> <span class="text-primary">Team</span></small></h2>
            </div>

            <div class="container">

                <!-- messages and errors -->
                @include('alert::alert')

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

<script src="//code.jquery.com/jquery-migrate-1.0.0.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<script src="//cdn.datatables.net/r/bs/dt-1.10.9/datatables.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
<script src="<?php echo asset('backend/plugins/form-datepicker/js/bootstrap-datepicker.js');?>"></script>

<!-- Layout and fixes plugins -->
<script type="text/javascript" src="<?php echo asset('backend/js/layouts/enquire.js');?>"></script>
<script type="text/javascript" src="<?php echo asset('backend/js/vendor/jquery/jquery.cookie.js');?>"></script>
<script type="text/javascript" src="<?php echo asset('backend/js/vendor/jquery/jquery.nicescroll.min.js');?>"></script>
<script type="text/javascript" src="<?php echo asset('backend/js/layouts/application.js');?>"></script>
<script type="text/javascript" src="<?php echo asset('backend/js/team.js');?>"></script>

</body>
</html>