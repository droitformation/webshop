$( document ).ready(function(event) {

    /*
    * Inner height on filter
    */

    function calculHeightOnFilter(){

        var innerHeight = $('#inner-content').outerHeight();
        innerHeight     = innerHeight + 30;

        $('#sidebar').css('height',innerHeight);
    }

	if($('#filtering')) {

		// Chosen init
		var chosenSelect = $("#arret-chosen").chosen();

		// Get dom elements
		var domArrets  = $('#filtering');
		var categories = ['cat','year'];
		
		var domFiltre  = $('#masterFilter');
		
		var filtres    = domFiltre.find('ul#arret-annees li a');
		var blockCat   = domArrets.find('.arrets');
		var arrets     = domArrets.find('.arrets div.arret');

		var activeClasses      = [];
		var activeSelectors    = [];
		var activeClassesOrder = [];		
		var activeCategories   = [];
				
		filtres.on('click', function(event) {
						
			event.preventDefault();
			event.stopPropagation();

			if($(this).hasClass('active')) 
			{
				$(this).removeClass('active');
			} 
			else 
			{
				filtres.removeClass('active');
				$(this).addClass('active');
			}
			
			filter();
		});
		
		chosenSelect.on('change', function(event) {
			categoryChange( $(this) );
			filter();
            calculHeightOnFilter();
		});
		
		var categoryChange = function( obj ) {

			if( obj.hasClass( 'category' ) ) {
			
				activeCategories = [];
				
				var $all = obj.find(":selected");
				
				$.each( $all, function( key, item ) {
					activeCategories.push( $(this).val() );
				});
			}
		};
		
		var filter = function() {

			activeClasses = {
				'cat':[],
				'year':[]
			};
	
			domFiltre.find('ul.annees li a.active').each(function(item,index) {
				activeClasses.year.push($(this).attr('rel'));
			});
			
			// Met les categories choisies dans le tableau activeClasses
			activeClasses.cat  = activeCategories;

			activeSelectors    = [];
			activeClassesOrder = [];
			
			if(activeClasses.cat.length > 0) 
			{
				activeClassesOrder.push('cat');
			}
			if(activeClasses.year.length > 0) 
			{
				activeClassesOrder.push('year');
			}

			// Si on a au moins 1 filtre cat et/ou year
			if(activeClassesOrder.length > 0) {
				
				$(activeClasses[activeClassesOrder[0]]).each( function( index1 , item1 ) {							
					
					// Si on a aussi des années
					if(activeClassesOrder.length > 1) 
					{					
						$(activeClasses[activeClassesOrder[1]]).each( function( index2 , item2) {

							activeSelectors.push(''+item1+'.'+item2);
						});						
					} 
					else 
					{
						activeSelectors.push(''+item1);
					}

				});
			}

			blockCat.removeClass('hidden');
            $('.analyses').removeClass('hidden');

            console.log(activeSelectors);
			
			if(activeSelectors.length == 0) 
			{
				arrets.removeClass('hidden');
			} 
			else 
			{
				// Hide all elements
				arrets.addClass('hidden');
				
				// Join all selector together so the search is exclusive
				var classes = activeSelectors.join('.');
				// Display the matching elements
				var selector = '#filtering .arrets .arret.' + classes;

				$( selector ).removeClass('hidden');

				// Hide empty category block when all children elements are hidden
				$(blockCat).each( function( item,index) {
				
					if($(this).find('div.arret').length == $(this).find('div.arret.hidden').length) 
					{
						$(this).addClass('hidden');
					}
					
				});

                if ( !$('.analyse:visible').length ) {
                    console.log('all are hidden');
                    $('.analyses').addClass('hidden');
                }

            }
		};
		
		// Clean the filter to show all arrets 
		var cleanFilters = function()
		{
			var chosenSelect = $(".chosen-select")[0];
			var filtres = domFiltre.find('ul.list li a');
			
			chosenSelect.selectedIndex = -1;
			chosenSelect.trigger("chosen:updated");
			
			categoryChange( chosenSelect );
			
			filtres.removeClass('active');
		};

	}
});

