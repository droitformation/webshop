<!DOCTYPE html>
<head>
    <meta charset="utf-8" />
	<title>Publications droit</title>
	
	<meta name="description" content="Publications droit, shop en ligne, Faculté de droit - Université de Neuchâtel, 2000 Neuchâtel">
	<meta name="author" content="Cindy Leschaud">
	<meta name="viewport" content="width=device-width">
	
    <!-- CSS Files
    ================================================== -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

	<!-- All css -->
	<link rel="stylesheet" href="<?php echo asset('frontend/pubdroit/css/bs.css');?>">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	<link rel="stylesheet" href="<?php echo asset('frontend/pubdroit/css/style.css');?>">
	<link rel="stylesheet" href="<?php echo asset('frontend/pubdroit/css/responsive.css');?>">

	<!-- Css Files Start -->
	<link rel="stylesheet" href="<?php echo asset('frontend/pubdroit/css/skins/red.css');?>">
	<link rel="stylesheet" href="<?php echo asset('frontend/pubdroit/css/update-responsive.css');?>">
    <link rel="stylesheet" href="<?php echo asset('frontend/css/selectric.css');?>">

	<!-- Bootstrap Css -->
	<link rel="stylesheet" href="<?php echo asset('frontend/pubdroit/css/main-slider.css');?>">

	<!-- Main Slider Css -->
	<!--[if lte IE 10]><link rel="stylesheet" href="<?php echo asset('frontend/pubdroit/css/customIE.css');?>"><![endif]-->
	<!-- Booklet Css -->
	<link rel="stylesheet" href="<?php echo asset('frontend/pubdroit/css/jquery.booklet.latest.css');?>">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo asset('css/validation/parsley.css');?>" media="screen" />
    <link rel="stylesheet" type="text/css" href="<?php echo asset('css/validation.css');?>">
    <link rel="stylesheet" type="text/css" href="<?php echo asset('frontend/pubdroit/css/checkout/checkout.css');?>">
	<noscript>
		<link rel="stylesheet" href="<?php echo asset('frontend/pubdroit/css/noJS.css');?>">
	</noscript>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.14.0/jquery.validate.js"></script>
    <script src="<?php echo asset('backend/js/validation/messages_fr.js');?>"></script>
    <script src="<?php echo asset('js/validation.js');?>"></script>

</head>
	<body>

		<!-- Start Main Wrapper -->
		<div class="wrapper">
			<!-- Start Main Header -->
			<!-- Start Top Nav Bar -->
			<section class="top-nav-bar">
				<section class="container">
					<section class="row">
						<section class="col-md-6">
							@include('frontend.pubdroit.partials.menu')
						</section>
						<section class="col-md-6 e-commerce-list text-right">
                            @if (!Auth::check())
                                <div>
                                    <a href="{{ url('auth/login')}}" class="navbar-btn navbar-login btn-default"><i class="fa fa-lock"></i>&nbsp; {{ trans('message.login') }}</a>
                                    <a href="{{ url('auth/register')}}" class="navbar-btn navbar-register btn-primary"><i class="fa fa-edit"></i>&nbsp; {{ trans('message.register') }}</a>
                                </div>
                            @endif
                            @if (Auth::check())
                                <ul class="top-nav2 pull-right">
                                    <li>Bonjour {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</li>
                                    <li><a href="{{ url('profil') }}">Mon compte</a></li>
                                    <li><a href="{{ url('auth/logout') }}">Déconnexion</a></li>
                                </ul>
                            @endif
						</section>
					</section>
				</section>
			</section>
			<!-- End Top Nav Bar -->

            <header id="main-header">
				<section class="container">

                    @include('partials.message')

					<section class="row">
						<section class="col-md-4 col-xs-12">
							<h1 id="logo"><a href="{{ url('/') }}"><img style="height: 75px; width:380px;" src="{{ asset('frontend/pubdroit/images/logo.svg') }}" /></a></h1>
						</section>
						<section class="col-md-8 col-xs-12">
                            <div class="c-btn">
                                <a href="{{ url('checkout/cart') }}" class="cart-btn">Panier</a>
                                <div class="btn-group">
                                    <a href="{{ url('checkout/cart') }}" class="btn btn-mini dropdown-toggle">
                                        @if(!Cart::content()->isEmpty())
                                            {{ Cart::count() }} {{ Cart::count() > 1 ? 'articles': 'article'}} - {{ number_format((float)Cart::total(), 2, '.', '') }} CHF
                                        @else
                                            0 article(s) - 0.00 CHF
                                        @endif
                                    </a>
                                </div>
                            </div>
						</section>
					</section>
				</section>
				<!-- Start Main Nav Bar -->

                <nav id="nav">
                    <section class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="navbar">
                                    <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target="#labels">
                                        <span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
                                    </button>
                                    <div class="nav-collapse collapse in" id="labels">
										@include('frontend.pubdroit.partials.label')
                                    </div>
                                    <div class="search-bar">
										<form action="{{ url('search') }}" method="post">{!! csrf_field() !!}
											<input name="term" type="text" value="Rechercher sur le site..." />
											<button type="submit" class="button-default">ok</button>
										</form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </nav><!-- /.navbar -->

                <!-- End Main Nav Bar -->
			</header>
			<!-- End Main Header -->

			<!-- Start Main Content Holder -->
			<section id="content-holder" class="container">
				<!-- Contenu -->
				@yield('content')
				<!-- Fin contenu -->
			</section>
			<!-- End Main Content Holder -->

			<!-- Start Footer Top 1 -->
			<section class="footer-top1">
				<section class="container">
					<section class="row">
						<figure class="col-md-6">
							<h4>Newsletter</h4>
							<p>Inscrivez simplement votre adresse email pour recevoir les nouveautés du site </p>

							@inject('newsworker', 'newsworker')
							<?php $newsletters = $newsworker->siteNewsletter($site->id); ?>
							@foreach($newsletters as $newsletter)
								@include('newsletter::Frontend.partials.subscribe', ['newsletter' => $newsletter])
							@endforeach

						</figure>
						<figure class="col-md-1"></figure>
						<figure class="col-md-5">
							<h4>Contact</h4>

							<div class="row">
								<div class="col-md-6">
									<h5><strong>{!! Registry::get('shop.infos.nom') !!}</strong></h5>
									{!! Registry::get('shop.infos.adresse') !!}
								</div>
								<div class="col-md-6">
									<p><a href="mailto:{{ Registry::get('shop.infos.email') }}"> <i class="fa fa-envelope"></i> &nbsp;{{ Registry::get('shop.infos.email') }}</a></p>
									<ul class="phon-list">
									   <li>Tél: {!! Registry::get('shop.infos.telephone') !!}</li>
									</ul>
								</div>
							</div>

						</figure>
					</section>
				</section>
			</section>
			<!-- End Footer Top 1 -->

			<!-- Start Main Footer -->
			<footer id="main-footer" class="social-ico-bar">
				<section class="container">
					<section class="row">
						<p class="col-md-6">© 2016  publications-droit.ch </p>
					</section>
				</section>
			</footer>
			<!-- End Main Footer -->

		</div>
		<!-- End Main Wrapper -->

		<!-- Javascript Files
    	================================================== -->

        <script src="<?php echo asset('frontend/pubdroit/js/lib.js');?>"></script>
        <script src="<?php echo asset('frontend/js/jquery.selectric.js');?>"></script>
		<script src="<?php echo asset('frontend/pubdroit/js/modernizr.js');?>"></script>
		<script src="<?php echo asset('frontend/pubdroit/js/easing.js');?>"></script>
		<script src="<?php echo asset('frontend/pubdroit/js/bs.js');?>"></script>
		<script src="<?php echo asset('frontend/pubdroit/js/bxslider.js');?>"></script>
		<script src="<?php echo asset('frontend/pubdroit/js/input-clear.js');?>"></script>
		<script src="<?php echo asset('frontend/pubdroit/js/range-slider.js');?>"></script>
		<script src="<?php echo asset('frontend/pubdroit/js/jquery.zoom.js');?>"></script>
		<script src="<?php echo asset('frontend/pubdroit/js/readmore.min.js');?>"></script>
		<script src="<?php echo asset('frontend/pubdroit/js/bookblock.js');?>"></script>
		<script src="<?php echo asset('frontend/js/jquery.slimscroll.min.js');?>"></script>
        <script src="<?php echo asset('frontend/pubdroit/js/interaction.js');?>"></script>
        <script src="<?php echo asset('frontend/pubdroit/js/custom.js');?>"></script>
        <script src="<?php echo asset('frontend/pubdroit/js/checkout/checkout.js');?>"></script>
		<script src="<?php echo asset('frontend/pubdroit/js/jquery.booklet.latest.js');?>"></script>

	</body>
</html>