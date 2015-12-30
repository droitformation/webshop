$(function() {

	// $("#e1").select2({'width' : 'element' });
	
	$('.selection').select2();	

	// Add scrollbar
	$('.product-left').tinyscrollbar();
				
	function InitIsotope( container, column_width, gutter_width ) 
	{
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
	

	var column_width  = 172;
	var column_height = 239;
	var gutter_width  = 15;
	var $container    = $('#content-shop');
	
	InitIsotope( $container, column_width, gutter_width );	
	
	// Zoom when over
	var base_width  = 172;
	var base_height = 239;
	var nb_px_plus  = 50;
	var new_width   = base_width + nb_px_plus;
	var new_height  = base_height + nb_px_plus;
	var zoom_speed  = 100;

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
	
});

