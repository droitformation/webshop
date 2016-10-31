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
    <link rel="stylesheet" href="<?php echo asset('frontend/common/css/selectric.css');?>">

	<!-- Bootstrap Css -->
	<link rel="stylesheet" href="<?php echo asset('frontend/pubdroit/css/main-slider.css');?>">

	<!-- Main Slider Css -->
	<!--[if lte IE 10]><link rel="stylesheet" href="<?php echo asset('frontend/pubdroit/css/customIE.css');?>"><![endif]-->
	<!-- Booklet Css -->
	<link rel="stylesheet" href="<?php echo asset('frontend/pubdroit/css/jquery.booklet.latest.css');?>">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo asset('common/css/parsley.css');?>" media="screen" />
    <link rel="stylesheet" type="text/css" href="<?php echo asset('common/css/validation.css');?>">
	<link rel="stylesheet" type="text/css" href="<?php echo asset('frontend/pubdroit/css/checkout/checkout.css');?>">
	<link rel="stylesheet" type="text/css" href="<?php echo asset('frontend/pubdroit/css/sweetalert.css');?>">

	<noscript>
		<link rel="stylesheet" href="<?php echo asset('frontend/pubdroit/css/noJS.css');?>">
	</noscript>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.14.0/jquery.validate.js"></script>
	<script src="<?php echo asset('common/js/messages_fr.js');?>"></script>
    <script src="<?php echo asset('common/js/validation.js');?>"></script>

</head>
	<body>

		<!-- Start Main Wrapper -->
		<div class="wrapper">
			<!-- Start Main Header -->

			<div id="preheader">
				<section class="container">
					<section class="row">
						<section class="col-md-8" id="preheader-menu">
							<ul class="pull-left">
								@if(!$menus->isEmpty())
									<?php $menu = $menus->where('position','main'); ?>
									@if(!$menu->isEmpty())
										<?php $menu = $menu->first()->load('pages'); ?>
										@if(!$menu->pages->isEmpty())
											@foreach($menu->pages as $page)
												<li>{!! $page->page_url !!}</li>
											@endforeach
										@endif
									@endif
								@endif
							</ul>
						</section>

						<section class="col-md-4 text-right login-profile">
							@if (!Auth::check())
								<div class="btn-group">
									<a href="{{ url('login')}}" class="btn btn-default navbar-login "><i class="fa fa-lock"></i>&nbsp; {{ trans('message.login') }}</a>
									<a href="{{ url('register')}}" class="btn navbar-register btn-primary navbar-login "><i class="fa fa-edit"></i>&nbsp; {{ trans('message.register') }}</a>
								</div>
							@endif
							@if (Auth::check())
								<div class="pull-right logged-profile">
									Bonjour {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
									<a class="btn btn-default btn-profile" href="{{ url('pubdroit/profil') }}">Mon compte</a>
									<form class="logout" action="{{ url('logout') }}" method="POST">{{ csrf_field() }}
										<button class="btn btn-default btn-xs" type="submit"><i class="fa fa-power-off" aria-hidden="true"></i></button>
									</form>
								</div>
							@endif
						</section>
					</section>
				</section>
			</div>

            <div class="container">
                @include('alert::alert')
                @include('backend.partials.message')
                @include('partials.confirmation')
            </div>

            <header id="main-header">
				<img src="{{ asset('files/uploads/book_shelf.jpg') }}" alt="homepage">
				<section class="container">

					<section class="row" id="header">
						<section class="col-md-6 col-xs-12">
							<h1 id="logo">
								<a href="{{ url('/') }}">
									<img style="width:510px; height: 115px" src="{{ asset('frontend/pubdroit/images/logo.svg') }}" />
								</a>
							</h1>
						</section>
						<section class="col-md-6 col-xs-12 text-right">
							@include('frontend.pubdroit.partials.panier')
						</section>
					</section>
				</section>
			</header>
			<!-- End Main Header -->

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
                                    <form action="{{ url('pubdroit/search') }}" method="post">{!! csrf_field() !!}
                                        <input name="term" type="text" placeholder="Rechercher sur le site..." />
                                        <button type="submit" class="button-default">ok</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </nav><!-- /.navbar -->

            <!-- End Main Nav Bar -->

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
							@if(isset($newsletters) && !$newsletters->isEmpty())
								<h4>Newsletter</h4>
								<p>Inscrivez simplement votre adresse email pour recevoir les nouveautés du site </p>

								@foreach($newsletters as $newsletter)
									@include('newsletter::Frontend.partials.subscribe', ['newsletter' => $newsletter])
								@endforeach
							@endif
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
			<div class="sites-logos-wrapper">
				<section class="container">
					<div class="row">
						<div class="col-md-12 sites-logos">
							<a target="_blank" href="http://droitmatrimonial.ch/"><img src="{{ asset('files/sites/matrimonial.png') }}" alt="matrimonial" /></a>
							<a target="_blank" href="http://bail.ch/"><img src="{{ asset('files/sites/bail.png') }}" alt="bail" /></a>
							<a target="_blank" href="http://droitpraticien.ch"><img src="{{ asset('files/sites/droitpraticien.png') }}" alt="droitpraticien" /></a>
							<a target="_blank" href="http://tribunauxcivils.ch"><img src="{{ asset('files/sites/tribunaux.png') }}" alt="tribunaux" /></a>
							<a target="_blank" href="http://droitenschemas.ch"><img src="{{ asset('files/sites/schemas.png') }}" alt="schemas" /></a>
							<a target="_blank" href="http://droitdutravail.ch"><img src="{{asset('files/sites/droittravail.png')}}" alt="droitdutravail" /></a>
							<a target="_blank" href="http://rjne.ch"><img src="{{ asset('files/sites/rjn.png') }}" alt="rjn" /></a>
							<a target="_blank" href="http://rcassurances.ch"><img src="{{ asset('files/sites/rca.png') }}" alt="rcassurances" /></a>
						</div>
					</div>
				</section>
			</div>
			<!-- Start Main Footer -->
			<footer id="main-footer" class="social-ico-bar">
				<section class="container">
					<section class="row">
						<p class="col-md-6">© {{ date('Y') }} {{ $site->nom }}</p>
					</section>
				</section>
			</footer>
			<!-- End Main Footer -->

		</div>
		<!-- End Main Wrapper -->

		<!-- Javascript Files
    	================================================== -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
        <script src="<?php echo asset('common/js/jquery.selectric.js');?>"></script>
		<script src="<?php echo asset('frontend/pubdroit/js/readmore.min.js');?>"></script>
		<script src="<?php echo asset('frontend/pubdroit/js/sweetalert.min.js');?>"></script>
        <script src="<?php echo asset('frontend/pubdroit/js/interaction.js');?>"></script>
        <script src="<?php echo asset('frontend/pubdroit/js/checkout/checkout.js');?>"></script>

	</body>
</html>