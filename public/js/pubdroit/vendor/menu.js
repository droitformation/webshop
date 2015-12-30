$(document).ready(function(){
	// Get the current hash
	var hash = window.location.hash;
	hash = hash.split('/');
	var next = 0;
	for (var i = 0; i < hash.length; i++) {
		if( next == 1 ) {
			next = hash[i];
		}
		if( hash[i] == 'cat') {
			next = 1;
		}
	};
	if( next != '' ) {
		hash = 	'#/cat/' + next;
	}
	// Add active class to the corresponding menu
	$('a[href*="' + hash + '"]').addClass('act');
});
