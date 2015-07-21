<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>SHOP</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SHOP">
    <meta name="author" content="Cindy Leschaud | @DesignPond">
    <meta name="token" content="<?php echo csrf_token(); ?>">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">

    <script src="<?php echo asset('js/jquery-1.11.3.min.js');?>"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
    <script src="http://code.jquery.com/jquery-migrate-1.0.0.js"></script>

    <!-- Checkout Files -->
    <link rel="stylesheet" type="text/css" href="<?php echo asset('css/checkout/checkout-cornerflat.css');?>" media="screen" />
    <link rel="stylesheet" type="text/css" href="<?php echo asset('css/checkout/checkout.css');?>" media="screen" />
    <link rel="stylesheet" type="text/css" href="<?php echo asset('css/user/profil.css');?>" media="screen" />
    <link rel="stylesheet" type="text/css" href="<?php echo asset('css/colloque/colloque.css');?>" media="screen" />
    <link rel="stylesheet" type="text/css" href="<?php echo asset('css/validation/parsley.css');?>" media="screen" />

    <script src="<?php echo asset('js/checkout/classie.js');?>"></script>
    <script src="<?php echo asset('js/checkout/checkout.js');?>"></script>
    <script src="<?php echo asset('js/colloque/inscription.js');?>"></script>
    <script src="<?php echo asset('js/interaction.js');?>"></script>
    <script src="<?php echo asset('js/validation/parsley.js');?>"></script>
    <script src="<?php echo asset('js/validation/fr.js');?>"></script>

    <!--[if lt IE 9]>
    <script type="text/javascript" src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/respond.js/1.1.0/respond.min.js"></script>
    <![endif]-->

    <base href="/">

</head>

<body>

    <div class="container">

        <!-- Navigation  -->
        @include('partials.header')
        @include('partials.message')

        @if(!Cart::content()->isEmpty() && (Request::is('/') || Request::is('product/*')))
            <div class="row">
                <div class="col-md-12">
                    <a class="btn btn-xl btn-info" data-toggle="collapse" href="#collapseCart" href="#"><i class="glyphicon glyphicon-shopping-cart"></i></a>
                    <span class="badge badge-notify">{{ Cart::count() }}</span>
                    <div class="collapse" id="collapseCart">
                        <!-- Cart  -->
                        @include('shop.partials.cart')
                        @include('shop.partials.cart-total')
                        <ul class="pager"><li class="next next-commander"><a href="{{ url('checkout/resume') }}">Commander <span aria-hidden="true">&rarr;</span></a></li></ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Contenu -->
        @yield('content')
        <!-- Fin contenu -->

    </div>
</body>
</html>
