<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>SHOP</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SHOP">
    <meta name="author" content="Cindy Leschaud | @DesignPond">
    <meta name="token" content="<?php echo csrf_token(); ?>">

    @include('partials.commonjs')

    <!-- Checkout Files -->
    <link rel="stylesheet" type="text/css" href="<?php echo asset('css/checkout/checkout.css');?>" media="screen" />
    <script src="<?php echo asset('js/checkout/checkout.js');?>"></script>

    <base href="/">
</head>
<body>

    <div class="container">

        <!-- Navigation  -->
        @include('frontend.partials.header')
        @include('partials.message')

        @if(!Cart::content()->isEmpty() && (Request::is('shop') || Request::is('shop/product/*')))
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
