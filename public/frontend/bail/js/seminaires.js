$( document ).ready(function(event) {
	
	if($('#seminaires')) {
			
		var domFiltre = $('.seminaire.filtre');
		
		// Chosen init
		var chosenSelect = $(".seminaire-chosen").chosen();
		
		// Get dom elements
		var domSeminaires = $('#seminaires');
		var categories    = ['cat','year','author'];
		
		var filtresOne = domFiltre.find('#seminaireannees li a');
		var blockCat   = domSeminaires.find('.sujets div.cat');
		var sujets     = domSeminaires.find('.sujets div.cat div.sujet');	
		
		console.log(filtresOne);
		
		var activeClasses      = [];
		var activeSelectors    = [];
		var activeClassesOrder = [];
		
		var activeCategories   = [];
		var activeAuthors      = [];
			
		filtresOne.on('click', function(event) {
		
			event.preventDefault();
			event.stopPropagation();
			
			if($(this).hasClass('active')) 
			{
				$(this).removeClass('active');
			} 
			else 
			{
				filtresOne.removeClass('active');
				$(this).addClass('active');
			}
			
			filter();
		});
		
		chosenSelect.on('change', function(event) {
		
			if($(this).hasClass('category')) 
			{			
				
				activeCategories = [];
				
				var $all = $(this).find(":selected");
				
				$.each( $all, function( key, item ) {
					activeCategories.push( $(this).val() );
				});
				
				filter();
				
			} 
			else if($(this).hasClass('author')) 
			{			
				activeAuthors = [];	
				
				console.log($(this));			
				
				var $allauthor = $(this).find(":selected");
				
				$.each( $allauthor, function( key, item ) {
					activeAuthors.push( $(this).val() );
				});
								
				filter();
			}
			
		});
		
		var filter = function() {
		
			activeClasses = {
				'cat'   :[],
				'year'  :[],
				'author':[]
			};
		
			domFiltre.find('#seminaireannees li a.active').each(function(item,index) {
				activeClasses.year.push($(this).attr('rel'));
			});
		
			activeClasses.cat    = activeCategories;
			activeClasses.author = activeAuthors;
			
			activeSelectors    = [];
			activeClassesOrder = [];
			
			if(activeClasses.cat.length > 0) {
				activeClassesOrder.push('cat');
			}
			if(activeClasses.year.length > 0) {
				activeClassesOrder.push('year');
			}
			if(activeClasses.author.length > 0) {
				activeClassesOrder.push('author');
			}
			if(activeClassesOrder.length > 0) {
				
				$(activeClasses[activeClassesOrder[0]]).each( function( index1 , item1 ) {	
				
					if(activeClassesOrder.length > 1) {
					
						
						$(activeClasses[activeClassesOrder[1]]).each( function( index2 , item2) {
						
							if(activeClassesOrder.length > 2) 
							{
								$(activeClasses[activeClassesOrder[2]]).each( function( index3 , item3) {
									activeSelectors.push(''+item1+'.'+item2+'.'+item3);
								});
							} 
							else 
							{
								activeSelectors.push(''+item1+'.'+item2);
							}
							
						});
						
					} else {
						activeSelectors.push(''+item1);
					}
				});
			}
			
			//console.log(activeSelectors);
			
			blockCat.removeClass('hidden');
			
			if(activeSelectors.length == 0) 
			{
				sujets.removeClass('hidden');
			} 
			else 
			{
				sujets.addClass('hidden');				
		
				$(activeSelectors).each(function(index,item) {
					console.log(item);
					$('#seminaires .sujets div.sujet.'+item).removeClass('hidden');
				});
				
				$(blockCat).each(function(item,index) {
				
					if($(this).find('div.sujet').length == $(this).find('div.sujet.hidden').length) {
						$(this).addClass('hidden');
					}
					
				});
			}
		};
		

	}
});

