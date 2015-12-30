// Spin.js
$.fn.spin = function(opts) {
	this.each(function() {
	var $this = $(this),
		data = $this.data();
	if (data.spinner) {
		data.spinner.stop();
		delete data.spinner;
	}
	if (opts !== false) {
		data.spinner = new Spinner($.extend({color: $this.css('color')}, opts)).spin(this);
	}
	});
	return this;
};

function InitIsotope( container, column_width, gutter_width ) {
	$.Isotope.prototype._getMasonryGutterColumns = function() {
		var gutter = this.options.masonry && this.options.masonry.gutterWidth || 0;
			containerWidth = this.element.width();

		this.masonry.columnWidth = this.options.masonry && this.options.masonry.columnWidth ||
						// or use the size of the first item
						this.$filteredAtoms.outerWidth(true) ||
						// if there's no items, use size of container
						containerWidth;

		this.masonry.columnWidth += gutter;

		this.masonry.cols = Math.floor( ( containerWidth + gutter ) / this.masonry.columnWidth );
		this.masonry.cols = Math.max( this.masonry.cols, 1 );
	};
	$.Isotope.prototype._masonryReset = function() {
		// layout-specific props
		this.masonry = {};
		// FIXME shouldn't have to call this again
		this._getMasonryGutterColumns();
		var i = this.masonry.cols;
		this.masonry.colYs = [];
		while (i--) {
			this.masonry.colYs.push( 0 );
		}
	};
	$.Isotope.prototype._masonryResizeChanged = function() {
		var prevSegments = this.masonry.cols;
		// update cols/rows
		this._getMasonryGutterColumns();
		// return if updated cols/rows is not equal to previous
		return ( this.masonry.cols !== prevSegments );
	};
	$.Isotope.prototype._masonryGetContainerSize = function() {
		var unusedCols = 0,
		i = this.masonry.cols;
		// count unused columns
		while ( --i ) {
			if ( this.masonry.colYs[i] !== 0 ) {
				break;
			}
			unusedCols++;
		}

		return {
			height : Math.max.apply( Math, this.masonry.colYs ),
			// fit container to columns that have been used;
			width : (this.masonry.cols - unusedCols) * this.masonry.columnWidth
		};
	};

	container.isotope({
		itemSelector : '.block',
		masonry : {
			columnWidth : column_width,
			gutterWidth: gutter_width
		},
		getSortData : {
			title : function ( $elem ) {
				return $elem.find('.title').text();
			}
		},
		sortBy : 'title',
		sortAscending : true
	});
}

function ratio(width, height, new_width){
	//original height / original width x new width = new height
	return parseInt(height/width * new_width);
}

function getSammyUrl() {
  // Construit le début de l'url
  var url = location.pathname;
  // S'il y a une querystring
  if(location.search != '') {
      // Récupère la querystring
      var querystring = location.search;
      // Enleve le point d'interogation
      querystring = querystring.substring(1);
      // Sépare par &
      var splat = querystring.split('&');
      // Parcours les valeurs
      var arr = [];
      for (var i = 0; i < splat.length; i++) {
        var item = splat[i].split('=');
        // On prend seuelement la valeur "id" et "L"
        if( item[0] == 'id' || item[0] == 'L' ) {
          // Ajoute la valeur au tableau
          arr.push(splat[i]);
        }
      };
      // On reconstruit la fin de l'url
      url += '?' + arr.join('&');
  }
  // On retourne
  return url;
}

function getAjaxUrl( action, id ) {
	// Construit le début de l'url
	var url = 'http://' + location.host + location.pathname + '/api';
	// Récupère la querystring
	var querystring = location.search;
	// Enleve le point d'interogation
	querystring = querystring.substring(1);
	// Sépare par &
	var splat = querystring.split('&');
	// Parcours les valeurs
	var arr = [];
	for (var i = 0; i < splat.length; i++) {
		var item = splat[i].split('=');
		// Si c'est la valeur "action" on ignore
		if( item[0] != 'action') {
			// Si on a reçu un ID on ne prend pas celui la
			if( typeof(id) !== "undefined" && item[0] == 'id' ) {
				continue;
			}
			// Ajoute la valeur au tableau
			arr.push(splat[i]);
		}
	};
	// Ajoute l'id reçu en paramètre
	if( typeof(id) !== "undefined" ) {
		arr.push( 'id=' + id );
	}
	// On ajoute notre action à nous
	arr.push( 'action=' + action );
	// On reconstruit la fin de l'url
	url += '?' + arr.join('&');
	// On retourne
	return url;
}

function loadIFrame( uid ) {
	var flashvars = {};
	flashvars.XMLFileName = 'pageflipdata.xml';
	flashvars.DataFolder = 'uploads/tx_commercepubdroit/' + uid + '/';

	if (swfobject.getQueryParamValue("page")) {
		flashvars.StartPage = swfobject.getQueryParamValue("page");
	} else {
		flashvars.StartPage = "1";
	}

	flashvars.StartAutoFlip = "true";
	flashvars.AutoStart = "true";
	var params = {};

	params.scale = "noscale";
	params.salign = "TL";
	params.wmode = "transparent";
	params.allowscriptaccess = "always";
	params.allowfullscreen = "true";
	params.menu = "true";
	params.bgcolor = "#FFFFFF";

	var attributes = {};
	swfobject.embedSWF( 'pageflip/pageFlip.swf', "pageflip", "920", "640", "10.0.0", false, flashvars, params, attributes);
}

$(function(){

	var current_category = '';
	var column_width     = 172;
	var column_height    = 239;
	var gutter_width     = 15;
	var $container = $('#content-isotope');
	// S'il trouve isoTope
	if(typeof($.Isotope) != 'undefined') {
		// S'il y a des div avec la class block
		if($('div.block').length > 0) {
			// On l'initialise
			InitIsotope( $container, column_width, gutter_width );
		}
	}

	$('#container').stickem({
		item: '#menu',
		container: '#container',
		start: 143,
		stickClass: 'stickit-menu'
	});
	$('#container').stickem({
		item: '#subMenu',
		container: '#container',
		start: 143,
		stickClass: 'stickit-submenu'
	});
	$('#container').stickem({
		item: '#basket',
		container: '#container',
		start: 143,
		stickClass: 'stickit-basket'
	});

	// Search
	$('button.clear').hide();
	$('input.search').keypress(function(e) {
		code= (e.keyCode ? e.keyCode : e.which);
		if (code == 13) {
			var querystring = $('input.search').val();
			if( querystring != '' ) {
				var target = '#/search/' + encodeURI( querystring ) + '/item/close';
				handleLink( target );
			}
		}
	});

	$('input.search').bind('keyup mouseup change',function(e){
		// Changes
		var $this = $(this);
		if( $this.val() == '' ) {
			$('button.clear').hide();
		} else {
			$('button.clear').show();
		}
	});

	$('button.search').click(function(){
			var querystring = $('input.search').val();
			if( querystring != '' ) {
				var target = '#/search/' + encodeURI( querystring ) + '/item/close';
				handleLink( target );
			}
	});
	function clear_search_element() {
		$('button.clear').hide();
		// Vide la zone de recherche
		$('input.search').val('');
		// Supprime l'element indiquant la recherche
		$('div.search a').detach();
	}

	$('button.clear').click(function(){
		// Clear all search elements
		clear_search_element();
		// Reset la vue
		handleLink( 'reset' );
	});

	function getSearch( querystring ) {
		var results = [];
		// Requette Ajax qui va chercher les résultats de la recherche
		$.ajax({
			type: 'POST',
			url: getAjaxUrl('search'),
			data: { search: querystring },
			async: false,
			success:function( textJson ){
				// For safety of execution we take only what is between { }
				var end = textJson.lastIndexOf(']');
				var start = textJson.indexOf('[');
				textJson = textJson.substring(start, end+1);
				// Convert JSON text to a JSON object
				var data = jQuery.parseJSON( textJson );
				results = data;
			}
		});
		return results;
	}

	function search_remove_click( e ) {
		e.preventDefault();
		handleLink('#/search/');
		$('input.search').val('');
		$(this).fadeOut().detach();
		return false;
	}

	// Taille du block (valeur ajouter a la taille de base)
	var nb_element_width  = 3;
	var nb_element_height = 2;
	var width  = ( (nb_element_width-1)  * column_width )  + ( (nb_element_width-1)  * gutter_width );
	var height = ( (nb_element_height-1) * column_height ) + ( (nb_element_height-1) * gutter_width );
	var speed  = 500;
	var isbig  = false;

	function openProduct( id ) {
		// Selector du block
		var block = $( '#' + id );

		// Si le block que l'on demande d'ouvrir est déjà ouvert
		if( block.hasClass('opened') ) {
			// Ca va pas du tout
			// On arrête tout
			return;
		} else {
			// Ferme les produits ouvert
			closeAll();
		}

		// Si l'item que l'on veut ouvrir est cacher (filtrer)
		if ( block.hasClass('isotope-hidden') ) {
			// On enlève les filtres
			handleLink('#/filter/all/item/' + id);
			return;
		}/**/

		// Zoom the link out !
		var link = block.find('a.item img');
		zoomOutWithoutEffect( link );

		// Ajoute une data pour connaitre l'etat
		block.addClass('opened');

			// Taille de l'image de cover (la nouvelle)
		var new_width = 240;
		var new_height = ratio( block.find('.cover img').width(), block.find('.cover img').height(), new_width );

		// Change la taille du conteneur isotope
		block.css({
			width: '+='+width,
			height: '+='+height
		});

		// Demande a isotope de recalculer le layout
		$container.isotope('reLayout', function() {
			var offset = 110;
			$('html, body, document').animate({
				scrollTop: block.offset().top - offset + 'px'
			}, 'slow');
		});
		// Cache le titre et la zone epuisé
		block.find('.title-holder').fadeOut('fast');

		// Crée un div spinner et l'ajoute au DOM
		block.append( $( '<div id="spinner"></div>' ) );

		// Requette Ajax qui va chercher les infos du produits
		$.ajax({
			type: 'POST',
			url: getAjaxUrl('infos'),
			data: { product_id: block.attr('id') },
			beforeSend:function(){
				// On active le spinner
				var opts = {
					width: 4, // The line thickness
					color: '#fff', // #rgb or #rrggbb
				};
				$('#spinner').spin(opts);
			},
			success:function( textJson ){
				// Supprime le spinner
				$("#spinner").spin(false);
				$('#spinner').detach();

				// Si ça a été fermé entre temps
				if ( ! block.hasClass('opened') ) {
					// On s'arrete
					return;
				}

				// For safety of execution we take only what is between { }
				/*
				var end = textJson.lastIndexOf('}');
				var start = textJson.indexOf('{');
				textJson = textJson.substring(start, end+1);
				*/
				// Convert JSON text to a JSON object
				var data = textJson;

				// Change l'image
				// With fade
				//block.find('.cover').append('<img src="' + data.cover_img + '" alt="' + data.title + '" width="' + new_width + 'px" height="'+new_height+'px" class="big" />').fadeIn();

				// List all the attributes
				var attributes = '';
				for ( x in data.attributes )
				{
					attributes += data.attributes[x].name + ' ' + data.attributes[x].value + '<br />';
				}

				// List all the image
				var images = '';
				for ( x in data.related )
				{
					if( typeof(data.related[x]) != 'undefined' && typeof(data.related[x].image) != 'undefined' ) {
						images += '<a href="#/item/' + data.related[x].id + '" class="other"><img src="' + data.related[x].image + '" alt="' + data.related[x].title + '" width="120px" /></a>';
					}
					
				}

				// Affichage
				var div_left = [];
				// Ajoute le titre
				div_left.push('<div class="product-title">' + data.title + '</div>');
				// Ajoute l'auteur
				div_left.push('<div class="product-author">' + data.author + '</div>');
				// Ajoute la description
				div_left.push('<div class="product-longdesc">' + data.longdesc + '</div>');

				var div_right = [];
				// Ajoute le prix
				div_right.push('<span class="product-price">' + data.price + '</span>');

				if( data.sold_out ) {
					// Ajoute le lien panier
					div_right.push('<span class="product-cart">Ajouter au panier</span>');
				} else {
					var classes = 'product-cart';
					var target = 'target="_blank"';
					if( !data.cart_external ) {
						classes += ' add-to-cart';
						target = '';
					}
					// Ajoute le lien panier
					div_right.push('<a href="' + data.cart_url + '" class="' + classes + '" '+target+'>Ajouter au panier</a>');
				}

				// Ajoute l'élement
				block.append( '<div class="product-left"><div class="scrollbar"><div class="track"><div class="thumb"><div class="end"></div></div></div></div><div class="viewport"><div class="overview">' + div_left.join('') + '</div></div></div><div class="product-right">' + div_right.join('') + '</div>' );

				$sold_out_elm = block.find('.item .sold-out');
				if( $sold_out_elm.length == 1) {
					$sold_out_elm.fadeIn('fast');
				}

				// S'il y a des livre en Cross selling
				if( data.related.length > 0 ) {
					block.css({
						height: '+='+height
					});
					$container.isotope('reLayout');
					// Animation
					block.find('.holder').animate(
						{
							height: '+='+height,
						}, speed
					);
					isbig = true;

					var div_bottom = [];
					div_bottom.push('<div class="line product-other-buy">D\'autres ont acheté<div class="related">' + images + '</div></div>');
					block.append( '<div class="product-bottom">' + div_bottom.join('') + '</div>' );
				}

				// Add scrollbar
				$('.product-left').tinyscrollbar();

				// Add to basket
				$('a.add-to-cart').click(function( e ) {
					e.preventDefault();

					// Get the basket
					var $basket = $('#basket');
					// Clear closing timeout
					clearTimeout( $basket.data('basketTimer') );
					// Find the parent container
					var $container = $basket.find('.tx-commerce-pi1');
					var url = $(this).attr('href');
					//url = url.replace( 'id=5', 'id=284' );
					$.ajax({
							type: 'GET',
							url: url,
							success:function( data ){
									// Success
									var html = $(data).html();
									// We load the html in the container
									$container.html( html );
									$basket.slideDown();
									// And rebind the event
									initSlider();
							}
					});
					// Auto close shopping cart after 3 sec
					$basket.data('basketTimer', setTimeout(function(){
						$('#basket').slideUp();
					}, 4000));
				});

				// Pas de pageFlip en ligne
				data.hasPageFlip = false;
				// Si cet element possède un pageflip
				if( data.hasPageFlip ) {
					// Le pageflip sur la couverture
					block.find('.cover').addClass('pageflip-ready').click(function(e){
						e.preventDefault();
						Shadowbox.open({
								content:    '<div id="pageflip" style="margin: 0; display: none;"><p class="pageflip_no_flash">Pour visualiser ce contenu vous avez besoin de la dernière version d\'Adobe Flash Player. <a href="http://get.adobe.com/fr/flashplayer/" target="_blank">Téléchargez la dernière version de Flash Player.</a></p></div>',
								player:     "html",
								title:      "Welcome",
								height:     640,
								width:      920,
								options: {
									onFinish: function(){
										loadIFrame( block.attr('id') );
									}
								}
						});
					});
				}

				// Ajoute les evenements sur les liens Other
				$('a.other').click(function(e){
					var target = e.currentTarget.hash;
					handleLink( target );
					e.preventDefault();
					return false;
				});
			},
			error:function(){
				// Supprime le spinner
				$("#spinner").spin(false);
				$('#spinner').detach();

				// Crée l'element DOM
				var div = $( '<div class="product-left"><p><strong>Oops!</strong> Une erreur s\'est produite, réessayée dans un petit moment.</p></div>' );
				// Ajoute l'élement
				block.append( div );
			}
		});

		// Animation
		block.find('.holder').animate(
			{
				width: '+='+width,
				height: '+='+height,
				backgroundColor: '#929495'
			}, speed, function() {
				block.append( $( '<a href="#/item/close" class="close"></a>' ) ).fadeIn('slow');
				$('.close').click(function( e ){
					var target = e.currentTarget.hash;
					handleLink( target );
					e.preventDefault();
					return false;
				});
			}
		).find('.cover').animate({
			top: '15px',
			right: '15px',
			width: new_width + 'px',
			height: new_height + 'px'
		}).find('img').animate({
			width: new_width + 'px',
			height: new_height + 'px'
		});
	}

	function closeProduct( id ) {
		var block = $( '#' + id );
		// Retire les boutons fermer
		$('.close').fadeOut('fast').detach();

		block.removeClass('opened');

		height_to_remove = height;
		if( isbig ) {
			height_to_remove = height*2;
			isbig = false;
		}

		// Remove the click on the cover (no more pageflip)
		$('.cover').removeClass('pageflip-ready').unbind('click');

		block.find( '.product-left, .product-right, .product-bottom' ).detach();
		block.css({
			width: '-='+width,
			height: '-='+height_to_remove
		});
		block.find('.title-holder').fadeIn('fast');
		block.find('.sold-out').fadeOut('fast');
		$container.isotope('reLayout');
		// Animation
		block.find('.holder').animate(
			{
				width: '-='+width,
				height: '-='+height_to_remove,
				backgroundColor: '#929495'
			}, speed
		).find('.cover').animate({
			top: '0',
			right: '0',
			width: column_width + 'px',
			height: column_height + 'px'
		}, 400, function() {
			// Fin de l'animation
			block.find('img.big').detach();
			block.find('.cover img').css({
				width: column_width + 'px',
				height: column_height + 'px'
			}).fadeIn('fast');
		}).find('img.big').animate({
			width: column_width + 'px',
			height: column_height + 'px'
		});
	}

	function closeAll() {
		$( '.opened' ).each(function() {
			closeProduct( $(this).attr('id') );
		});
	}

	function matcher(term, text, option) {
		var select_id = $(option).parent().attr('id');
		var cat = current_category;
		var val = $(option).val();
		var selector = '.cat_' + cat + '.' + select_id + '_' + val;
		var count = $(selector).length;
		if( count == 0 ) {
			return false;
		} else {
			return true;
		}
	}

	var select2_options = {
		allowClear: true,
		minimumResultsForSearch: 100,
		matcher: matcher,
		placeholder: ''
	}

	select2_options.placeholder = 'Collections';
	$('.select2.filters-categories').select2( select2_options )
	.on("change", function(e) {
		var target = '#/filter/' + e.val;
		handleLink( target );
	});
	select2_options.placeholder = 'Auteurs';
	$('.select2.filters-authors').select2( select2_options )
	.on("change", function(e) {
		var target = '#/author/' + e.val;
		handleLink( target );
	});
	select2_options.placeholder = 'Thèmes';
	$('.select2.filters-themes').select2( select2_options )
	.on("change", function(e) {
		var target = '#/theme/' + e.val;
		handleLink( target );
	});

	$(".select2").on("open", function() {
		$('#container').stickem({
			item: '.select2-drop',
			container: '#container',
			start: 143,
			stickClass: 'stickit-select2-drop',
			onUnstick: function( item ) {
				var $select = $('.select2');
				var top = $select.offset().top + $select.height();
				item.$elem.css( 'top', top + 'px' );
			}
		});
		setTimeout(function(){
			$('.select2-scrollbar').tinyscrollbar();
		}, 50);
	});

	function handleLink( target ) {
	
		var reset = false;
		if( target == 'reset' ) {
			reset = true;
		}
		var args = {
			cat: '',
			filter: '',
			author: '',
			theme: '',
			search: '',
			item: ''
		};

		current_hash = location.hash;
		// Retire le #/ du début
		current_hash = current_hash.substring(2);
		target = target.substring(2);
		destination = '';
		// On sépare les arguments
		var elem = current_hash.split('/');
		// On remplit notre objet avec les valeur de l'url actuel
		var name = null;
		for (var i = 0; i < elem.length; i++) {
			if( i % 2 == 0 ) {
				name = elem[i];
			} else {
				args[name] = elem[i];
			}
		};
		// On fait pareil avec l'url cible
		var elem = target.split('/');
		// On remplit notre objet avec les valeur de l'url cible
		var name = null;
		for (var i = 0; i < elem.length; i++) {
			if( i % 2 == 0 ) {
				name = elem[i];
			} else {
				args[name] = elem[i];
			}
		};

		// Si l'on reset
		if( reset ) {
			args.filter = '';
			args.author = '';
			args.theme = '';
			args.search = '';
			args.item = '';
		}

		// Reconstruit le lien
		var url = [];
		// En mode recherche
		if( args.search != '' ) {
			url.push('search/' + args.search);
		}
		// Mode normale
		else {
			if( args.cat != '' ) {
				url.push( 'cat/' + args.cat );
			}
			if(args.filter != '') {
				if( args.filter != 'all' ) {
					url.push('filter/' + args.filter);
				}
			}
			if(args.author != '') {
				if( args.author != 'all' ) {
					url.push('author/' + args.author);
				}
			}
			if(args.theme != '') {
				if( args.theme != 'all' ) {
					url.push('theme/' + args.theme);
				}
			}
		}

		// Ajoute l'item
		if(args.item != '') {
			if( args.item != 'close' ) {
				url.push('item/' + args.item);
			} else {
				closeAll();
			}
		}

		destination = url.join('/');

		app.setLocation( getSammyUrl() + '#/' + destination);
	}

	$('a.item').click(function( e ) {
		$(this).find('img').stop();
		handleLink( e.currentTarget.hash );
		e.preventDefault();
		return false;
	});

	$('a.reset').click(function( e ) {
		handleLink( 'reset' );
		e.preventDefault();
		return false;
	});

	// Zoom when over
	var base_width = 172;
	var base_height = 239;
	var nb_px_plus = 50;
	var new_width = base_width + nb_px_plus;
	var new_height = base_height + nb_px_plus;
	var zoom_speed = 100;

	function zoomIn( $this ) {
		$this.data('timerId', setTimeout(function(){
			$this.parents('.block').css({ zIndex: 490 });
			$this.css({ zIndex: 490, position: 'absolute' });
			$this.addClass('shadow');
			$this.stop(true).animate({
				width: new_width,
				height: new_height,
				left: '-' + (nb_px_plus/2) + 'px',
				top: '-' + (nb_px_plus/2) + 'px'
			}, zoom_speed);
		}, 100));
	}
	function zoomOut( $this ) {
		// Si ouvert on ne fait pas l'effet
		if( $this.parents('.block').hasClass('opened') ) {
			return;
		}
		clearTimeout( $this.data('timerId') );
		setTimeout(function(){
			$this.removeClass('shadow');
		}, zoom_speed - 50);
		$this.stop(true).animate({
			width: base_width,
			height: base_height,
			left: 0,
			top: 0
		}, zoom_speed, function(){
			$this.parents('.block').css({ zIndex: 'auto' });
			$this.css({ zIndex: 'auto', position: 'static' });
		});
	}
	function zoomOutWithoutEffect( $this ) {
		$this.data('hover',false);
		clearTimeout( $this.data('timerId') );
		$this.removeClass('shadow');
		$this.stop(true).css({
			width: base_width,
			height: base_height,
			left: 0,
			top: 0
		});
		$this.parents('.block').css({ zIndex: 'auto' });
		$this.css({ zIndex: 'auto', position: 'static' });
	}

	$('.holder').hover(
		function(){
			var $this = $(this);
			var $parent = $this.parents('.block');
			if( !$parent.hasClass('opened') ) {
				zoomIn( $parent.find('img') );
			}
		}, function(){
			var $this = $(this);
			var $parent = $this.parents('.block');
			zoomOut( $parent.find('img') );
		}
	);

	var app = $.sammy('.main-shop', function() {
		var default_cat = 'publications';
		
		// Default route
		this.get('#/', function(context) {
			
			console.log('started');
			// Remove everything
			closeAll();
			// Clear the filters select
			$('.select2').select2("val", "all" );
			$('.select2').select2("enable");
			// Apply filter
			$container.isotope({
				filter: '*',
				sortAscending: true
			});
			$container.isotope('reLayout');
			handleLink( '#/cat/' + default_cat );
		});

		// Recherche dans les publications
		this.get('#/search/([a-zA-Z0-9%&\.\*]+)/?(?:(item)/([0-9]+))?/?', function(context) {
			// Clear the filters select
			$('.select2').select2("val", "all" );
			$('.select2').select2("disable");
			// Remove current active menu
			$('a.act').removeClass('act');			
			//
			var args = {
				search: this.params.splat[0],
				item: this.params.splat[2]
			};

			if( args.search != '' ) {
				$('input.search').val(args.search);
				// Get the values for the search
				var results = getSearch( args.search );
				$('div.search a').detach();
				$('div.search').append('<a href="javascript:void(0)" class="search-remove"> ' + args.search + '</a>');
				$('a.search-remove').click(search_remove_click);
				var filters = '.id_' + results.join( ', .id_' );
			}

			$('button.clear').show();
			// Apllique les option et reLayout
			$container.isotope({
				filter: filters
			});
			$container.isotope('reLayout');

			if( args.item != '' ) {
				openProduct( args.item );
			} else {
				closeAll();
			}
		});

		this.get('#/(?:(cat)/([a-zA-Z0-9_-]+))?/?(?:(filter)/([a-zA-Z0-9_-]+))?/?(?:(author)/([a-zA-Z0-9_-]+))?/?(?:(theme)/([a-zA-Z0-9_-]+))?/?(?:(item)/([0-9]+))?/?', function(context) {
		
			$('.select2').select2("enable");
			
			var name = null;
			var id   = null;
			var isotope_options = {};
			// Vide la recherche
			$('input.search').val('');
			$('button.clear').hide();
			$('div.search a').detach();

			var args = {
				cat: this.params.splat[1],
				filter: this.params.splat[3],
				author: this.params.splat[5],
				theme: this.params.splat[7],
				item: this.params.splat[9]
			};

			if( args.cat == '' ) {
				handleLink( '#/cat/' + default_cat );
			}

			// Contient les filtres pour isotope
			var isotope_filters = '';
			// S'il y a une catégorie on l'ajoute
			if( args.cat != '' ) {
				current_category = args.cat;
				isotope_filters += '.cat_' + args.cat;
				// Remove current active menu
				$('a.act').removeClass('act');
				// Get the current hash
				var hash = '#/cat/' + args.cat;
				// Add active class to the corresponding menu
				$('a[href*="' + hash + '"]').addClass('act');
			}
			// Ajoute le filtre
			if( args.filter != '' ) {
				isotope_filters += '.col_' + args.filter;
				// Set the select
				$('.select2.filters-categories').select2("val", args.filter );
			} else {
				$('.select2.filters-categories').select2('val', '');
			}
			// Ajoute l'auteur
			if( args.author != '' ) {
				isotope_filters += '.aut_' + args.author;
				// Set the select
				$('.select2.filters-authors').select2("val", args.author );
			} else {
				$('.select2.filters-authors').select2('val', '');
			}
			// Ajoute le theme
			if( args.theme != '' ) {
				isotope_filters += '.dom_' + args.theme;
				// Set the select
				$('.select2.filters-themes').select2("val", args.theme );
			} else {
				$('.select2.filters-themes').select2('val', '');
			}

			// Si aucun filtres n'a été ajouté
			if( isotope_filters == '' ) {
				// On  accepte tout
				isotope_filters = '*';
			}
			// Apllique les option et reLayout
			$container.isotope({
				filter: isotope_filters
			});
			$container.isotope('reLayout');
			
			// Si l'on a reçu un item à ouvrir
			//if( args.item != '' &&  !$('#' + args.item).hasClass('isotope-hidden') ) {
			if( args.item != '') {
				openProduct( args.item );
			} else {
				handleLink('#/item/close');
				//closeAll();
			}
		});
	});

    app.log = function() {};
    // Lance sammy avec comme route de base #/
    app.run(getSammyUrl() + '#/');
    app.debug = true;
    
});
