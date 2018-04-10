$(function() {

	$(".chosen-select").chosen();

	jQuery(function($){
		$.datepicker.regional['fr'] = {
			closeText: 'Fermer',
			prevText: 'Précédent',
			nextText: 'Suivant',
			currentText: 'Aujourd\'hui',
			monthNames: ['Janvier','Février','Mars','Avril','Mai','Juin',
				'Juillet','Août','Septembre','Octobre','Novembre','Décembre'],
			monthNamesShort: ['Janv.','Févr.','Mars','Avril','Mai','Juin',
				'Juil.','Août','Sept.','Oct.','Nov.','Déc.'],
			dayNames: ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'],
			dayNamesShort: ['Dim.','Lun.','Mar.','Mer.','Jeu.','Ven.','Sam.'],
			dayNamesMin: ['D','L','M','M','J','V','S'],
			weekHeader: 'Sem.',
			dateFormat: 'dd/mm/yy',
			firstDay: 1,
			isRTL: false,
			showMonthAfterYear: false,
			yearSuffix: ''};
		$.datepicker.setDefaults($.datepicker.regional['fr']);
	});
	
	$( "#input-datepicker" ).datepicker({
		 changeMonth: true,
		 changeYear: true,
		 yearRange: "-100:+0"
	});

	$.validator.addMethod("valueNotEquals", function(value, element, arg){
	  return arg != value;
	}, "Value must not equal arg.");

	$("#calculette").validate({
		  rules   : {canton: { valueNotEquals: "" }},
		  messages: {canton: { valueNotEquals: "Choisissez un canton" }}
	});

	var base_url = location.protocol + "//" + location.host+"/bail/";

	$( "#calculette" ).submit(function( event ) {
		event.preventDefault();

		var loyer  = $("#input-loyer").val();
		var date   = $("#input-datepicker").val();
		var canton = $("#input-canton").val();

		var $div = $('#calculatorResult');
		// Post all infos to controller
		$.ajax({
			 type: 'post',
			 data: { loyer:loyer, date:date, canton:canton , _token: $("meta[name='_token']").attr('content') },
			 success: function(data) {

			 	if(data)
			 	{
					$div.empty();
		 			var newLoyer = '<div class="lines result">\
									  <div class="nouveau_loyer lines clear">\
									  	  <span class="label-input">Votre nouveau loyer (CHF)</span><span class="value">'  + data.loyer + '</span>\
									  </div>\
									  <div class="difference lines clear">\
									  	  <span class="label-input">Différence</span><span class="value">'  + data.difference + '</span>\
									  </div>\
									</div>\
									<div class="details">\
										<div class="lines taux_start clear">\
											<span class="label-input">Taux d\'intérêt de référence de départ</span><span class="value">'  + data.taux_depart + '</span>\
										</div>\
										<div class="lines taux_dest clear">\
											<span class="label-input">Taux d\'intérêt de référence actuel</span>\
											<span class="value">'  + data.taux_actuel + '</span>\
											<span class="dates">('  + data.taux_date + ')</span>\
										</div>\
										<div class="lines ipc_start clear">\
											<span class="label-input">IPC de départ</span>\
											<span class="value">'  + data.ipc_depart + '</span>\
										</div>\
										<div class="lines ipc_dest clear">\
											<span class="label-input">IPC actuel</span>\
											<span class="value">'  + data.ipc_actuel + '</span>\
											<span class="dates">('  + data.ipc_date + ')</span>\
										</div>\
										<div class="lines dates clear"><span class="label-input">IPC base décembre 1982</span></div>\
									</div>';

				 	$div.append(newLoyer);
				 }
			 },
			 url: base_url + 'loyer'
		});

	});

	var height  = $('#content-wrapper').height();
	var content = $('#mainContent').height();
	content     = content + 60;
	
	var width = $('#appComponent').width();

	if(width > 860){
		$('#mainContent').css({ 'min-height' : height + 170 });
	}

});