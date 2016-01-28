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

	<!-- Css Files Start -->
	<link rel="stylesheet" href="<?php echo asset('frontend/pubdroit/css/style.css');?>">
	<link rel="stylesheet" href="<?php echo asset('frontend/pubdroit/css/skins/red.css');?>">
	<link rel="stylesheet" href="<?php echo asset('frontend/pubdroit/css/update-responsive.css');?>">

	<!-- All css -->
	<link rel="stylesheet" href="<?php echo asset('frontend/pubdroit/css/bs.css');?>">

	<!-- Bootstrap Css -->
	<link rel="stylesheet" href="<?php echo asset('frontend/pubdroit/css/main-slider.css');?>">

	<!-- Main Slider Css -->
	<!--[if lte IE 10]><link rel="stylesheet" href="<?php echo asset('frontend/pubdroit/css/customIE.css');?>"><![endif]-->
	<!-- Booklet Css -->
	<link rel="stylesheet" href="<?php echo asset('frontend/pubdroit/css/jquery.booklet.latest.css');?>">

	<noscript>
		<link rel="stylesheet" href="<?php echo asset('frontend/pubdroit/css/noJS.css');?>">
	</noscript>

</head>
	<body>

		<!-- Start Main Wrapper -->
		<div class="wrapper">
			<!-- Start Main Header -->
			<!-- Start Top Nav Bar -->
			<section class="top-nav-bar">
				<section class="container-fluid container">
					<section class="row-fluid">
						<section class="span6">
							<ul class="top-nav">
								<li><a href="index.html" class="active">Home page</a></li>
								<li><a href="grid-view.html">Shop</a></li>
								<li><a href="blog.html">Blog</a></li>
								<li><a href="shortcodes.html">Short Codes</a></li>
								<li><a href="blog-detail.html">News</a></li>
								<li><a href="contact.html">Contact Us</a></li>
							</ul>
						</section>
						<section class="span6 e-commerce-list">
							<ul>
								<li>Bienvenue! <a href="checkout.html">Login</a> or <a href="checkout.html">Créer un compte</a></li>
								<li class="p-category"><a href="#">eng</a> <a href="#">de</a> <a href="#">fr</a></li>
							</ul>
							<div class="c-btn">
								<a href="cart.html" class="cart-btn">Panier</a>
								<div class="btn-group">
									<button data-toggle="dropdown" class="btn btn-mini dropdown-toggle">0 item(s) - 0.00 CHF<span class="caret"></span></button>
									<ul class="dropdown-menu">
										<li><a href="#">Voir</a></li>
										<li><a href="#">Supprimer</a></li>
									</ul>
								</div>
							</div>
						</section>
					</section>
				</section>
			</section>
			<!-- End Top Nav Bar -->
			<header id="main-header">
				<section class="container-fluid container">
					<section class="row-fluid">
						<section class="span4">
							<h1 id="logo">
								<a href="{{ url('/') }}">
									<img style="height: 75px; width:380px;" src="frontend/pubdroit/images/logo.svg" />
								</a>
							</h1>
						</section>
						<section class="span8">
							<ul class="top-nav2">
								<li><a href="checkout.html">Mon compte</a></li>
								<li><a href="cart.html">Panier</a></li>
								<li><a href="checkout.html">Checkout</a></li>
							</ul>
							<div class="search-bar">
								<input name="" type="text" value="Rechercher sur le site..." />
								<input name="" type="button" value="Rechercher" />
							</div>
						</section>
					</section>
				</section>
				<!-- Start Main Nav Bar -->
				<nav id="nav">
					<div class="navbar navbar-inverse">
						<div class="navbar-inner">
							<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
							<div class="nav-collapse collapse">
								<ul class="nav">
									<li><a href="{{ url('categorie/1') }}"><i class="fa fa-star"></i> &nbsp; Nouveautés</a></li>
									<li><a href="{{ url('domaines') }}"><i class="fa fa-bookmark"></i> &nbsp; Collections</a></li>
									<li><a href="{{ url('categories') }}"><i class="fa fa-tags"></i> &nbsp;Thèmes</a></li>
									<li><a href="{{ url('authors') }}"><i class="fa fa-users"></i> &nbsp;Auteurs</a></li>
								</ul>
							</div>
							<!--/.nav-collapse -->
						</div>
						<!-- /.navbar-inner -->
					</div>
					<!-- /.navbar -->
				</nav>
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
						<figure class="span3">
							<h4>Newsletter</h4>
							<p>Subscribe to be the first to know about Best Deals and Exclusive Offers!</p>
							<input name="" type="text" class="field-bg" value="Enter Your Email"/>
							<input name="" type="submit" value="Subscribe" class="sub-btn" />
						</figure>
						<figure class="span3">
							<h4>Twitter</h4>
							<ul class="tweets-list">
								<li>Bookshoppe’- WooCommerce theme by crunchpress http<a href="#">://z.8o/XcexW23Q #envato</a></li>
								<li>Bookshoppe’- WooCommerce theme by crunchpress http<a href="#">://z.8o/XcexW23Q #envato</a></li>
							</ul>
						</figure>
						<figure class="span3">
							<h4>Location</h4>
							<p>5/23, Loft Towers, Business Center, 6th Floor, Media City, Dubai.</p>
          <span>
          <ul class="phon-list">
			  <li>(971) 438-555-314</li>
			  <li>(971) 367-252-333</li>
		  </ul>
          </span> <span class="mail-list"> <a href="#">info@companyname</a><br />
          <a href="#">jobs@companyname.com</a> </span> </figure>
						<figure class="span3">
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
						<figure class="span4">
							<h4>BestSellers</h4>
							<ul class="f2-img-list">
								<li>
									<div class="left"><a href="book-detail.html"><img src="frontend/pubdroit/images/image19.jpg" /></a></div>
									<div class="right"> <strong class="title"><a href="book-detail.html">fields</a></strong> <span class="by-author">by Arnold Grey</span> <span class="f-price">$127.55</span> </div>
								</li>
								<li>
									<div class="left"><a href="book-detail.html"><img src="frontend/pubdroit/images/image31.jpg" /></a></div>
									<div class="right"> <strong class="title"><a href="book-detail.html">Garfield</a></strong> <span class="by-author">by Arnold Grey</span> <span class="f-price">$127.55</span> </div>
								</li>
								<li>
									<div class="left"><a href="book-detail.html"><img src="frontend/pubdroit/images/image32.jpg" /></a></div>
									<div class="right"> <strong class="title"><a href="book-detail.html">Penselviniya</a></strong> <span class="by-author">by Arnold Grey</span> <span class="f-price">$127.55</span> </div>
								</li>
								<li>
									<div class="left"><a href="book-detail.html"><img src="frontend/pubdroit/images/image33.jpg" /></a></div>
									<div class="right"> <strong class="title"><a href="book-detail.html">Exemption</a></strong> <span class="by-author">by Arnold Grey</span> <span class="f-price">$127.55</span> </div>
								</li>
								<li>
									<div class="left"><a href="book-detail.html"><img src="frontend/pubdroit/images/image34.jpg" /></a></div>
									<div class="right"> <strong class="title"><a href="book-detail.html">Penfield</a></strong> <span class="by-author">by Arnold Grey</span> <span class="f-price">$127.55</span> </div>
								</li>
								<li>
									<div class="left"><a href="book-detail.html"><img src="frontend/pubdroit/images/image32.jpg" /></a></div>
									<div class="right"> <strong class="title"><a href="book-detail.html">Doors</a></strong> <span class="by-author">by Arnold Grey</span> <span class="f-price">$127.55</span> </div>
								</li>
							</ul>
						</figure>
						<figure class="span4">
							<h4>Top Rated Books</h4>
							<ul class="f2-img-list">
								<li>
									<div class="left"><a href="book-detail.html"><img src="frontend/pubdroit/images/image35.jpg" alt=""/></a></div>
									<div class="right"> <strong class="title"><a href="book-detail.html">A little rain</a></strong> <span class="by-author">by Arnold Grey</span> <span class="rating-bar"><img src="frontend/pubdroit/images/rating-star.png" alt="Rating Star"/></span> </div>
								</li>
								<li>
									<div class="left"><a href="book-detail.html"><img src="frontend/pubdroit/images/image33.jpg" alt="" /></a></div>
									<div class="right"> <strong class="title"><a href="book-detail.html">Son of Arabia</a></strong> <span class="by-author">by Arnold Grey</span> <span class="rating-bar"><img src="frontend/pubdroit/images/rating-star.png" alt="Rating Star"/></span> </div>
								</li>
								<li>
									<div class="left"><a href="book-detail.html"><img src="frontend/pubdroit/images/image32.jpg" alt="" /></a></div>
									<div class="right"> <strong class="title"><a href="book-detail.html">Serpents</a></strong> <span class="by-author">by Arnold Grey</span> <span class="rating-bar"><img src="frontend/pubdroit/images/rating-star.png" alt="Rating Star"/></span> </div>
								</li>
								<li>
									<div class="left"><a href="book-detail.html"><img src="frontend/pubdroit/images/image34.jpg" alt="" /></a></div>
									<div class="right"> <strong class="title"><a href="book-detail.html">Guns</a></strong> <span class="by-author">by Arnold Grey</span> <span class="rating-bar"><img src="frontend/pubdroit/images/rating-star.png" alt="Rating Star"/></span> </div>
								</li>
								<li>
									<div class="left"><a href="book-detail.html"><img src="frontend/pubdroit/images/image19.jpg" alt=""/></a></div>
									<div class="right"> <strong class="title"><a href="book-detail.html">Garfield</a></strong> <span class="by-author">by Arnold Grey</span> <span class="rating-bar"><img src="frontend/pubdroit/images/rating-star.png" alt="Rating Star"/></span> </div>
								</li>
								<li>
									<div class="left"><a href="book-detail.html"><img src="frontend/pubdroit/images/image35.jpg" alt="" /></a></div>
									<div class="right"> <strong class="title"><a href="book-detail.html">Wolfman</a></strong> <span class="by-author">by Arnold Grey</span> <span class="rating-bar"><img src="frontend/pubdroit/images/rating-star.png" alt="Rating Star"/></span> </div>
								</li>
							</ul>
						</figure>
						<figure class="span4">
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
							<article class="span6">
								<p>© 2016  publications-droit.ch </p>
							</article>
							<article class="span6 copy-right">
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
		<script src="<?php echo asset('frontend/pubdroit/js/modernizr.js');?>"></script>
		<script src="<?php echo asset('frontend/pubdroit/js/easing.js');?>"></script>
		<script src="<?php echo asset('frontend/pubdroit/js/bs.js');?>"></script>
		<script src="<?php echo asset('frontend/pubdroit/js/bxslider.js');?>"></script>
		<script src="<?php echo asset('frontend/pubdroit/js/input-clear.js');?>"></script>
		<script src="<?php echo asset('frontend/pubdroit/js/range-slider.js');?>"></script>
		<script src="<?php echo asset('frontend/pubdroit/js/jquery.zoom.js');?>"></script>
		<script src="<?php echo asset('frontend/pubdroit/js/readmore.min.js');?>"></script>
		<script src="<?php echo asset('frontend/pubdroit/js/bookblock.js');?>"></script>
		<script src="<?php echo asset('frontend/pubdroit/js/custom.js');?>"></script>
		<script src="<?php echo asset('frontend/pubdroit/js/jquery.booklet.latest.js');?>"></script>

	</body>
</html>