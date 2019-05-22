<!DOCTYPE html>
<head>
    <meta charset="utf-8" />
	<title>Publications droit</title>
	
	<meta name="description" content="Publications droit, shop en ligne, Faculté de droit - Université de Neuchâtel, 2000 Neuchâtel">
	<meta name="viewport" content="width=device-width">

	<!-- Meta LinkedIn -->
	@if(\Request::is('pubdroit/colloque/*') && isset($colloque))
		<meta property='og:title' content="{{ $colloque->titre }}"/>
		<meta property='og:image' content="{{ $colloque->frontend_illustration }}"/>
		<meta property='og:description' content="{{ $colloque->description }}"/>
		<meta property='og:url' content="{{ url('pubdroit/colloques/'.$colloque->id) }}" />
		<meta name="author" content="Publications droit, Faculté de droit, UniNE">
	@else
		<meta name="author" content="Cindy Leschaud">
	@endif
	<!-- Fin Meta -->

    <!-- CSS Files
    ================================================== -->
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

	<!-- All css -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">	<link rel="stylesheet" href="<?php echo secure_asset('frontend/pubdroit/css/style.css');?>">

	<!-- Css Files Start -->
	<link rel="stylesheet" href="<?php echo secure_asset('frontend/2019/css/style.css');?>">
    <link rel="stylesheet" href="<?php echo secure_asset('frontend/common/css/selectric.css');?>">
	<link rel="stylesheet" href="<?php echo secure_asset('common/css/sites.css');?>">
	<link rel="stylesheet" href="<?php echo secure_asset('common/css/jquery.fancybox.min.css');?>">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">

    <link rel="stylesheet" type="text/css" href="<?php echo secure_asset('common/css/validation.css');?>">
	<link rel="stylesheet" type="text/css" href="<?php echo secure_asset('frontend/pubdroit/css/checkout/checkout.css');?>">
	<link rel="stylesheet" type="text/css" href="<?php echo secure_asset('frontend/pubdroit/css/sweetalert.css');?>">

	<script
			src="//code.jquery.com/jquery-3.4.1.min.js"
			integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
			crossorigin="anonymous"></script>

    <script src="//cdn.jsdelivr.net/npm/jquery-validation@1.19.0/dist/jquery.validate.min.js"></script>
	<script src="<?php echo secure_asset('common/js/messages_fr.js');?>"></script>
    <script src="<?php echo secure_asset('common/js/validation.js');?>"></script>
	<script src="<?php echo secure_asset('common/js/jquery.fancybox.min.js');?>"></script>
	<script src="<?php echo secure_asset('common/js/common.js');?>"></script>

	<script src="//platform-api.sharethis.com/js/sharethis.js#property=5beaaa2158e49d001b369ef0&product=inline-share-buttons"></script>
</head>
	<body>

		<!-- Start Main Wrapper -->
		<div id="wrapper" class="d-flex flex-column">
			<!-- Start Main Header -->

			<section class="container">

				<nav id="preheader">
					<div><a id="logo" href="{{ url('/') }}">publications<span>-droit</span></a></div>

					<div id="mainnav">
						<a href="{{ url('/') }}" class="active">Accueil</a>
						@if(!$menus->isEmpty())
							<?php $menu = $menus->where('position','main'); ?>
							@if(!$menu->isEmpty())
								<?php $menu = $menu[1]->load('active'); ?>
								@if(!$menu->active->isEmpty())
									@foreach($menu->active as $page)
										{!! $page->page_url !!}
									@endforeach
								@endif
							@endif
						@endif
					</div>

					@inject('cart_worker', 'App\Droit\Shop\Cart\Worker\CartWorker')

					<div id="nav_btn" class="text-right">
						@if (!Auth::check())
							<div class="btn-group">
								<a href="{{ url('login')}}" class="btn btn-default navbar-login "><i class="fa fa-lock"></i>&nbsp; {{ trans('message.login') }}</a>
								<a href="{{ url('register')}}" class="btn navbar-register btn-primary navbar-login "><i class="fa fa-edit"></i>&nbsp; {{ trans('message.register') }}</a>
							</div>
						@endif
						@if (Auth::check())

							@if(Auth::user()->role_admin)
								<a class="btn btn-admin " href="{{ url('admin') }}">Admin</a>
							@endif


							<div class="dropdown">
								<button class="btn btn-danger dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									{{ Auth::user()->first_name }} {{ Auth::user()->last_name }} |
									{{ $cart_worker->countCart() > 0 ? $cart_worker->totalCart() : '0.00' }} CHF
								</button>
								<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
									<a class="dropdown-item" href="{{ url('pubdroit/checkout/cart') }}"><i class="fa fa-shopping-cart"></i> Panier</a></a>
									<a class="dropdown-item" href="{{ url('pubdroit/profil') }}"><i class="fa fa-user"></i>  Mon compte</a>
								</div>
							</div>

						{{--	<form class="logout" action="{{ url('logout') }}" method="POST">{{ csrf_field() }}
								<button class="btn btn-default btn-xs" type="submit"><i class="fa fa-power-off" aria-hidden="true"></i></button>
							</form>--}}

						@endif
					</div>
					<div class="text-right"><img width="105" height="65" src="{{ secure_asset('frontend/2019/images/unine.svg') }}" alt="homepage"></div>
				</nav>
			</section>


            <header id="main-header-banner">
				<div id="illu">
					<div class="search-bar">
						<form action="{{ url('pubdroit/search') }}" method="post">{!! csrf_field() !!}
							<div class="input-group">
								<input type="text" class="form-control" name="term"  placeholder="Rechercher sur le site...">
								<div class="input-group-append">
									<button class="btn btn-outline-secondary" type="submit" id="button-addon2">OK</button>
								</div>
							</div>
						</form>
					</div>
					<span><img src="{{ secure_asset('frontend/2019/images/books.jpg') }}" alt="homepage"></span>
				</div>
				<div id="filters">
					<div class="container">
						<div class="col">
							<div class="dropdown">
								<button class="btn btn-dropdown dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Dropdown button
								</button>
								<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
									<a class="dropdown-item" href="#">Action</a>
									<a class="dropdown-item" href="#">Another action</a>
									<a class="dropdown-item" href="#">Something else here</a>
								</div>
							</div>

							<div class="navbar">
								<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target="#labels">
									<span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
								</button>
								<div class="nav-collapse collapse in" id="labels">
									@include('frontend.pubdroit.partials.label')
								</div>
							</div>
						</div>
					</div>
				</div>
			</header>
			<!-- End Main Header -->


			@include('alert::bootstrap')

			<div class="container">
				@include('backend.partials.message')
				@include('partials.confirmation')
			</div>

			<!-- Start Main Content Holder -->
			<section id="content-wrapper">
				<div class="container">
					<!-- Contenu -->
					@yield('content')
					<!-- Fin contenu -->
				</div>
			</section>
			<!-- End Main Content Holder -->

			<!-- Start Footer Top 1 -->
			<section class="footer-top1">
				<section class="container">
					<section class="row">

						<figure class="col-md-6">
							@if(isset($newsletters) && !$newsletters->isEmpty())
								<h4>Newsletter</h4>
								<p>Si vous souhaitez être informé des dernières nouveautés, inscrivez-vous simplement à notre newsletter.</p>
								@foreach($newsletters as $newsletter)
									<p><a data-fancybox data-type="iframe"
										  data-src="{{ url('site/subscribe/'.$newsletter->site->id) }}"
										  class="btn btn-default btn-profile btn-block"
										  href="javascript:;">
											Je m'inscris!
										</a></p>
									{{--@include('frontend.newsletter.partials.subscribe', ['newsletter' => $newsletter, 'return_path' => 'pubdroit'])--}}

									<p style="margin-top: 8px;">Je souhaite me <a data-fancybox data-type="iframe"
																				  data-src="{{ url('site/unsubscribe/'.$newsletter->site->id) }}"
																				  href="javascript:;">désinscrire</a></p>
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
			@include('partials.logos')
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
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <script src="<?php echo secure_asset('common/js/jquery.selectric.js');?>"></script>
		<script src="<?php echo secure_asset('frontend/pubdroit/js/sweetalert.min.js');?>"></script>
        <script src="<?php echo secure_asset('frontend/pubdroit/js/interaction.js');?>"></script>
        <script src="<?php echo secure_asset('frontend/pubdroit/js/checkout/checkout.js');?>"></script>

		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-114403548-1"></script>
		<script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', 'UA-114403548-1');
		</script>

	</body>
</html>