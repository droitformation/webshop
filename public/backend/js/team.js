$( function() {

    var base_url = location.protocol + "//" + location.host+"/";

    /*
     * Datepicker
     * */
    $.fn.datepicker.dates['fr'] = {
        days: ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'],
        daysShort: ['Dim','Lun','Mar','Mer','Jeu','Ven','Sam'],
        daysMin: ['Di','Lu','Ma','Me','Je','Ve','Sa'],
        months: ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'],
        monthsShort: ['Jan','Fév','Mar','Avr','Mai','Jun','Jul','Aoû','Sep','Oct','Nov','Déc'],
        today: "Aujourd'hui",
        clear: "Clear"
    };

    $('.datePicker').datepicker({
        format: 'yyyy-mm-dd',
        language: 'fr'
    });

});

$(document).ready(ajustamodal);
$(window).resize(ajustamodal);
function ajustamodal() {
    var altura = $(window).height() - 255; //value corresponding to the modal heading + footer
    $(".ativa-scroll").css({"height":altura,"overflow-y":"auto"});
}

