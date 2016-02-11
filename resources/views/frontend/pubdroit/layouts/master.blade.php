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
							<ul class="top-nav">
								<li><a href="grid-view.html">Newsletter</a></li>
								<li><a href="blog.html">Bachelors en droit</a></li>
								<li><a href="shortcodes.html">Masters</a></li>
								<li><a href="blog-detail.html">CAS/MAS/DAS</a></li>
								<li><a href="contact.html">Contact</a></li>
							</ul>
						</section>
						<section class="col-md-6 e-commerce-list text-right">
                            @if (!Auth::check())
                                <div>
                                    <a href="{{ url('auth/login')}}" class="navbar-btn navbar-login">
										<i class="fa fa-lock"></i>&nbsp; {{ trans('message.login') }}
									</a>
                                    <a href="{{ url('auth/register')}}" class="navbar-btn navbar-register">
										<i class="fa fa-edit"></i>&nbsp; {{ trans('message.register') }}
									</a>
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
						<section class="col-md-4">
							<h1 id="logo">
								<a href="{{ url('/') }}"><img style="height: 75px; width:380px;" src="{{ asset('frontend/pubdroit/images/logo.svg') }}" /></a>
							</h1>
						</section>
						<section class="col-md-8">
                            <div class="c-btn">
                                <a href="cart.html" class="cart-btn">Panier</a>
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
                                    <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                    </button>
                                    <div class="nav-collapse collapse in">
										@include('frontend.pubdroit.partials.label')
                                    </div>
                                    <div class="search-bar">
                                        <input name="" type="text" value="Rechercher sur le site..." />
                                        <input name="" type="button" class="button-default" value="ok" />
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
			<section id="content-holder" class="container-fluid container">

				<!-- Contenu -->
				@yield('content')
				<!-- Fin contenu -->

			</section>
			<!-- End Main Content Holder -->
			<!-- Start Footer Top 1 -->
			<section class="container-fluid footer-top1">
				<section class="container">
					<section class="row-fluid">
						<figure class="col-md-3">
							<h4>Newsletter</h4>
							<p>Subscribe to be the first to know about Best Deals and Exclusive Offers!</p>
							<input name="" type="text" class="field-bg" value="Enter Your Email"/>
							<input name="" type="submit" value="Subscribe" class="sub-btn" />
						</figure>
						<figure class="col-md-3">
							<h4>Twitter</h4>
							<ul class="tweets-list">
								<li>Bookshoppe’- WooCommerce theme by crunchpress http<a href="#">://z.8o/XcexW23Q #envato</a></li>
								<li>Bookshoppe’- WooCommerce theme by crunchpress http<a href="#">://z.8o/XcexW23Q #envato</a></li>
							</ul>
						</figure>
						<figure class="col-md-3">
							<h4>Location</h4>
							<p>5/23, Loft Towers, Business Center, 6th Floor, Media City, Dubai.</p>
          <span>
          <ul class="phon-list">
			  <li>(971) 438-555-314</li>
			  <li>(971) 367-252-333</li>
		  </ul>
          </span> <span class="mail-list"> <a href="#">info@companyname</a><br />
          <a href="#">jobs@companyname.com</a> </span> </figure>
						<figure class="col-md-3">
							<h4>Opening Time</h4>
							<p>Monday-Friday ______8.00 to 18.00</p>
							<p>Saturday ____________ 9.00 to 18.00</p>
							<p>Sunday _____________10.00 to 16.00</p>
							<p>Every 30 day of month Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
						</figure>
					</section>
				</section>
			</section>
			<!-- End Footer Top 1 -->
			<!-- Start Footer Top 2 -->
			<section class="container-fluid footer-top2">
				<section class="social-ico-bar">
					<section class="container">
						<section class="row-fluid">
							<div id="socialicons" class="hidden-phone"> <a id="social_linkedin" class="social_active" href="#" title="Visit Google Plus page"><span></span></a> <a id="social_facebook" class="social_active" href="#" title="Visit Facebook page"><span></span></a> <a id="social_twitter" class="social_active" href="#" title="Visit Twitter page"><span></span></a> <a id="social_youtube" class="social_active" href="#" title="Visit Youtube"><span></span></a> <a id="social_vimeo" class="social_active" href="#" title="Visit Vimeo"><span></span></a> <a id="social_trumblr" class="social_active" href="#" title="Visit Vimeo"><span></span></a> <a id="social_google_plus" class="social_active" href="#" title="Visit Vimeo"><span></span></a> <a id="social_dribbble" class="social_active" href="#" title="Visit Vimeo"><span></span></a> <a id="social_pinterest" class="social_active" href="#" title="Visit Vimeo"><span></span></a> </div>
							<ul class="footer2-link">
								<li><a href="about-us.html">About Us</a></li>
								<li><a href="contact.html">Customer Service</a></li>
								<li><a href="order-recieved.html">Orders Tracking</a></li>
							</ul>
						</section>
					</section>
				</section>
				<section class="container">
					<section class="row-fluid">
						<figure class="col-md-4">
							<h4>BestSellers</h4>

						</figure>
						<figure class="col-md-4">
							<h4>Top Rated Books</h4>

						</figure>
						<figure class="col-md-4">
							<h4>From the blog</h4>
							<ul class="f2-pots-list">
								<li> <span class="post-date2">28 APR</span> <a href="blog-detail.html">Corso completo di grafica web completo di grafi dare...</a> <span class="comments-num">6 comments</span> </li>
								<li> <span class="post-date2">28 APR</span> <a href="blog-detail.html">Corso completo di grafica web completo di grafi dare...</a> <span class="comments-num">6 comments</span> </li>
								<li> <span class="post-date2">28 APR</span> <a href="blog-detail.html">Corso completo di grafica web completo di grafi dare...</a> <span class="comments-num">6 comments</span> </li>
							</ul>
						</figure>
					</section>
				</section>
			</section>
			<!-- End Footer Top 2 -->
			<!-- Start Main Footer -->
			<footer id="main-footer">
				<section class="social-ico-bar">
					<section class="container">
						<section class="row-fluid">
							<article class="col-md-6">
								<p>© 2016  publications-droit.ch </p>
							</article>
							<article class="col-md-6 copy-right">
							</article>
						</section>
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