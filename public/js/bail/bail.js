function initAccordion(){
	// Get URL of current page
	var baseUrl = $('#base').attr('href');
	
	var currentUrl = window.location.href.replace(baseUrl, '');

	$('.accordionContent').hide();
	
	$('.accordionPart').each(function(i) {

		var ok       = false;
		var newClass = '';		
		
		// jurisprudence
		if($( this ).hasClass('jurisprudence')) 
		{
			if($('#arrets').length > 0) 
			{
				console.log('dans jurisprudence');
				ok                  = true;
				newClass            = 'jurisprudence';
				overflowFixClass    = 'jurisprudence';
				overflowFixSelector = '.accordionContent.jurisprudence';
				$( ".accordionContent.seminaire" ).hide();
			}
		}
		
		// seminaires
		if($( this ).hasClass('seminaire')) 
		{
			if($('#seminaires').length > 0) 
			{	
				console.log('dans seminaires');
				ok                  = true;
				newClass            = 'seminaire';
				overflowFixClass    = 'seminaire';
				overflowFixSelector = '.accordionContent.seminaire';
				$( ".accordionContent.jurisprudence" ).hide();
			}
		}
		
		if(ok) 
		{

			$( this ).addClass('accordion');
			$( this ).addClass('active');
			$( this ).next().addClass('accordionContent');
			$( this ).next().addClass(newClass);
		}
		
	});

	// Set active to active
	var show = false;
	
	$('#rightmenu h4.accordion').each(function(index) {

	   if($( this ).hasClass('active')) {
		  show = index;
	   }
	   
	});
	
	$("#rightmenu").accordion({ 
		header: "h4.accordion" , 
		heightStyle: 'content', 
		collapsible: true , 
		active: show ,
		create: function( event, ui ) { 
			
		}
	});
	
		
	// Cancel links on accordion toggler
	$('#rightmenu h4.accordion a').on('click', function(event) {
		event.preventDefault();
	});
	
 
}

function initRevueMenu() {
	var ulMenu = $('.revueMenu ul.menu');
	if(ulMenu.length > 0) {
		ulMenu = ulMenu[0];
		var parent = ulMenu.getParent();
		var newUl_1 = new Element('ul.menu2col1');
		newUl_1.inject(parent);
		var items = ulMenu.getElements('li');
		var nbItems = items.length;
		for(i = 0; i<Math.ceil(nbItems/2); i++) {
			items[i].inject(newUl_1);
		}
		var newUl_2 = new Element('ul.menu2col2');
		newUl_2.inject(parent);
		for(i = Math.ceil(nbItems/2); i<nbItems; i++) {
			items[i].inject(newUl_2);
		}
		ulMenu.dispose();
	}
}


$( document ).ready(function() {
	//bailInit();
	
	initAccordion();

	
});
