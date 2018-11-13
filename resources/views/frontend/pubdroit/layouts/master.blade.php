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
	<link rel="stylesheet" href="<?php echo secure_asset('frontend/pubdroit/css/bs.css');?>">
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	<link rel="stylesheet" href="<?php echo secure_asset('frontend/pubdroit/css/style.css');?>">

	<!-- Css Files Start -->
	<link rel="stylesheet" href="<?php echo secure_asset('frontend/pubdroit/css/skins/red.css');?>">
	<link rel="stylesheet" href="<?php echo secure_asset('frontend/pubdroit/css/update-responsive.css');?>">
    <link rel="stylesheet" href="<?php echo secure_asset('frontend/common/css/selectric.css');?>">
	<link rel="stylesheet" href="<?php echo secure_asset('frontend/pubdroit/css/user/profil.css');?>">
	<link rel="stylesheet" href="<?php echo secure_asset('common/css/sites.css');?>">

	<!-- Bootstrap Css -->
	<link rel="stylesheet" href="<?php echo secure_asset('frontend/pubdroit/css/main-slider.css');?>">

	<!-- Main Slider Css -->
	<!--[if lte IE 10]><link rel="stylesheet" href="<?php echo secure_asset('frontend/pubdroit/css/customIE.css');?>"><![endif]-->
	<!-- Booklet Css -->
	<link rel="stylesheet" href="<?php echo secure_asset('frontend/pubdroit/css/jquery.booklet.latest.css');?>">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo secure_asset('common/css/parsley.css');?>" media="screen" />
    <link rel="stylesheet" type="text/css" href="<?php echo secure_asset('common/css/validation.css');?>">
	<link rel="stylesheet" type="text/css" href="<?php echo secure_asset('frontend/pubdroit/css/checkout/checkout.css');?>">
	<link rel="stylesheet" type="text/css" href="<?php echo secure_asset('frontend/pubdroit/css/sweetalert.css');?>">

	<noscript>
		<link rel="stylesheet" href="<?php echo secure_asset('frontend/pubdroit/css/noJS.css');?>">
	</noscript>

	<link rel="stylesheet" href="<?php echo secure_asset('frontend/pubdroit/css/responsive.css');?>">

    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
    <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.14.0/jquery.validate.js"></script>
	<script src="<?php echo secure_asset('common/js/messages_fr.js');?>"></script>
    <script src="<?php echo secure_asset('common/js/validation.js');?>"></script>

</head>
	<body>

		<!-- Start Main Wrapper -->
		<div class="wrapper">
			<!-- Start Main Header -->

			<div id="preheader">
				<section class="container">
					<section class="row">
						<section class="col-md-7" id="preheader-menu">
							<ul>
								@if(!$menus->isEmpty())
									<?php $menu = $menus->where('position','main'); ?>
									@if(!$menu->isEmpty())
										<?php $menu = $menu->first()->load('pages_active'); ?>
										@if(!$menu->pages_active->isEmpty())
											@foreach($menu->pages_active as $page)
												<li>{!! $page->page_url !!}</li>
											@endforeach
										@endif
									@endif
								@endif
							</ul>
						</section>

						<section class="col-md-5 text-right login-profile">
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

                                    @if(Auth::user()->role_admin)
									    <a class="btn btn-admin" href="{{ url('admin') }}">Admin</a>
									@endif

									<form class="logout" action="{{ url('logout') }}" method="POST">{{ csrf_field() }}
										<button class="btn btn-default btn-xs" type="submit"><i class="fa fa-power-off" aria-hidden="true"></i></button>
									</form>
								</div>
							@endif
						</section>
					</section>
				</section>
			</div>

			@include('alert::bootstrap')

			<div class="container">
                @include('backend.partials.message')
                @include('partials.confirmation')
            </div>

            <header id="main-header">
				<img src="{{ secure_asset('images/pubdroit/book_shelf.jpg') }}" alt="homepage" class="bg_header">
				<section class="container">

					<section class="row" id="header">
						<section class="col-md-6 col-xs-12">
							<h1 id="logo">
								<a href="{{ url('/') }}">
									<img style="max-width:510px; max-height: 115px" src="{{ secure_asset('frontend/pubdroit/images/logo.svg') }}" />
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
								<p>Si vous souhaitez être informé des dernières nouveautés, inscrivez-vous simplement à notre newsletter en insérant votre adresse e-mail.</p>
								@foreach($newsletters as $newsletter)
									<h4>{{ $newsletter->titre }}</h4>
									@include('frontend.newsletter.partials.subscribe', ['newsletter' => $newsletter, 'return_path' => 'pubdroit'])
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
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
        <script src="<?php echo secure_asset('common/js/jquery.selectric.js');?>"></script>
		<script src="<?php echo secure_asset('frontend/pubdroit/js/readmore.min.js');?>"></script>
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