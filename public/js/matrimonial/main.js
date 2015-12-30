$(function() {
	
	// $( "#accordion" ).accordion();
	
	$('#toggleNewsletter').click(function() {
		
		if ($('.toggleNewsletter').is(":hidden"))
		{
			$(this).addClass('toggleActive');
		}
		else
		{
			$(this).removeClass('toggleActive');
		} 
		
		$('.toggleNewsletter').slideToggle('fast');
		
		return false;
	});
	
	$("#arret-chosen").chosen();

});