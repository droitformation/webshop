$( function() {

    $('.redactor').redactor({
        minHeight  : 250,
        maxHeight: 450,
        focus: true,
        plugins: ['advanced','imagemanager','filemanager'],
        fileUpload : 'uploadFileRedactor?_token=' + $('meta[name="_token"]').attr('content'),
        imageUpload: 'uploadRedactor?_token=' + $('meta[name="_token"]').attr('content'),
        imageManagerJson: 'imageJson',
        fileManagerJson: 'fileJson',
        buttons    : ['html','|','formatting','bold','italic','|','unorderedlist','orderedlist','outdent','indent','|','image','file','link','alignment']
    });

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

    $('body').on('click','.deleteAction',function(event){

        var $this  = $(this);
        var action = $this.data('action');
        var answer = confirm('Voulez-vous vraiment supprimer : '+ action +' ?');

        if (answer){
            return true;
        }
        return false;
    });

    // The url to the application
    var base_url = location.protocol + "//" + location.host+"/";

    $( "#searchUser" ).autocomplete({
        source: base_url + 'admin/search',
        minLength: 3,
        select: function( event, ui )
        {
            console.log(ui);

            $('#inputUser').html('<input type="hidden" value="' + ui.item.value + '" name="user_id">');

            $('#choiceUser').html(
                '<h4><strong>Utilisateur</strong></h4>'
                + '<address>'
                +  ui.item.label + '<br/>'
                +  ui.item.adresse.adresse + '<br/>'
                +  ui.item.adresse.npa + ' ' +  ui.item.adresse.ville
                + '</address>'
            );

            $(this).val('');

            return false;
        }
    }).autocomplete( "instance" )._renderItem = function( ul, item )
    {
        return $( "<li>" ).append( "<a>" + item.label + "<span>" + item.desc + "</span></a>" ).appendTo( ul );
    };

    $( "#colloqueSelection" ).change(function() {

        var optionSelected = $("option:selected", this);
        var valueSelected  = optionSelected.val();
        var textSelected   = optionSelected.text();

        $("#inputolloque").html('<input type="hidden" value="' + valueSelected + '" name="colloque_id">');
        $("#choiceColloque").html('<h4><strong>Colloque:</strong></h4><p>' + textSelected + '</p>');
    });

    $('#formInscription').on('submit', function (e) {

        e.preventDefault();

        var datastring = $("#formInscription").serialize();

        $.ajax({
            type: "POST",
            url : base_url + "admin/inscription/type",
            data: datastring,
            success: function(data) {
                $('#selectInscription').html(data);
            },
            error: function(){
                alert('error handing here');
            }
        });

    });

    $('body').on("click", '#cloneBtn' ,function(e) {

        var $wrapper_clone  = $('#wrapper_clone');
        var $fieldset_clone = $('#fieldset_clone');

        e.preventDefault(); e.stopPropagation();

        var clone = $fieldset_clone.clone();

        var length   = $('.field_clone').length;
        var $options = clone.find('.option-input');
        var $radios  = clone.find('.group-input');

        $radios.each(function(){

            var name = $(this).attr('name');
            var name = name.replace('groupes[0]', 'groupes[' + length + ']');
            $(this).attr('name', name);
        });

        $options.each(function(){
            var oname = $(this).attr('name');
            var oname = oname.replace('options[0]', 'options[' + length + ']');
            $(this).attr('name', oname);
        });

        clone.attr('id', '');
        clone.prepend('<a href="#" class="remove">x</a>');
        clone.appendTo($wrapper_clone);
    });

    $('body').on("click", '.remove' ,function(e) {
        e.preventDefault(); e.stopPropagation();

        $(this).closest('fieldset').remove();

    });

});