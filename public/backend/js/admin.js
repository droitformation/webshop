$( function() {

    var base_url = location.protocol + "//" + location.host+"/";

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

    $('.redactorSimple').redactor({
        minHeight: 50,
        maxHeight: 100,
        focus    : true,
        buttons  : ['formatting','bold','italic','|','unorderedlist']
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
        var what   = $this.data('what');

        var what = (0 === what.length ? 'supprimer' : what);
        var answer = confirm('Voulez-vous vraiment ' + what + ' : '+ action +' ?');

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
        $('#colloqueSelection').trigger('change');
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
                alert('problème avec la séléction de l\'utilisateur');
            }
        });
    });

    /*
     * Choice of location for event
     * */
    if($('#endroitSelect').length && $('#endroitSelect').val() != "") {
        getEndroit($('#endroitSelect').val());
    }

    $(document).on('change', '#endroitSelect' , function (e){
        e.preventDefault();
        getEndroit($(this).val());
    });

    function getEndroit(id){
        $.ajax({
            type: "GET",
            url : base_url + "admin/colloque/location/" + id,
            success: function(data) {
                if(data)
                {
                    var map = (data.map ? '<img style="width:100%;" src="files/colloques/cartes/'+ data.map +'" alt="Map"><small>La carte du bon</small>' : '<span style="display: block;" class="text-danger">il n\'existe pas de carte</span>');
                    var html = '<div class="thumbnail"><div class="row"><div class="col-md-3">'
                        + map
                        + '</div>'
                        + '<div class="col-md-8">'
                        + '<h4>'+ data.name + '</h4>'
                        + '<p>' + data.adresse + '</p>'
                        + '</div></div>'
                        + '</div>';

                    $('#showEndroit').html(html);
                }
            },
            error: function(){ alert('problème avec la séléction de l\'endroit'); }
        });
    }

    /*
     * Choice of adresse for event
     * */
    if($('#adresseSelect').length && $('#adresseSelect').val() != "") {
        getAdresse($('#adresseSelect').val());
    }

    $(document).on('change', '#adresseSelect' , function (e) {
        e.preventDefault();
        getAdresse($(this).val());
    });

    function getAdresse(id){
        $.ajax({
            type: "GET",
            url : base_url + "admin/colloque/adresse/" + id,
            success: function(data) {
                if(data)
                {
                    var logo = (data.logo ? '<img style="max-width:100%;max-height:100px;" src="files/logos/'+ data.logo +'" alt="Logo">' : '<span class="text-danger">il n\'existe pas de logo</span>');
                    var html = '<div class="row"><div class="col-md-3">' + logo + '</div>'
                        + '<div class="col-md-8">'
                        + '<p>' + data.adresse + '</p>'
                        + '</div></div>';
                    $('#showAdresse').html(html);
                }
            },
            error: function(){ alert('problème avec la séléction de l\'adresse'); }
        });
    }

    /*
    * Colloques options and prices
    */
    $('.addPrice').on("click",function(e) {

        e.preventDefault();e.stopPropagation();

        var $form = $(this).closest('div.price');
        var $main = $(this).closest('.form-group');
        var data  = $form.find("select,textarea,input").serialize();

        $.ajax({
            type : "POST",
            url  : base_url + "admin/colloque/addprice",
            data : { data: data, _token: $("meta[name='_token']").attr('content') },
            success: function(data) {
                $main.find('.priceWrapper').empty();
                $main.replaceWith(data);
            },
            error: function(){alert('problème avec l\'ajout du prix');}
        });
    });

    $('body').on("click",'.removePrice', function(e) {

        e.preventDefault();e.stopPropagation();

        var price = $(this).data('id');
        var $main = $(this).closest('.form-group');

        $.ajax({
            type : "POST",
            url  : base_url + "admin/colloque/removeprice",
            data : { id: price, _token: $("meta[name='_token']").attr('content') },
            success: function(data) {
                $main.replaceWith(data);
            },
            error: function(){alert('problème avec la suppresion du prix');}
        });
    });

    $('.addOption').on("click",function(e) {

        e.preventDefault();e.stopPropagation();

        var $form = $(this).closest('div.option');
        var $main = $(this).closest('.form-group');
        var data  = $form.find("select,textarea,input").serialize();

        $.ajax({
            type : "POST",
            url  : base_url + "admin/colloque/addoption",
            data : { data: data, _token: $("meta[name='_token']").attr('content') },
            success: function(data) {
                $main.find('.priceWrapper').empty();
                $main.replaceWith(data);
            },
            error: function(){alert('problème avec l\'ajout de \'option');}
        });
    });

    $('body').on("click",'.removeOption', function(e) {

        e.preventDefault();e.stopPropagation();

        var price = $(this).data('id');
        var $main = $(this).closest('.form-group');

        $.ajax({
            type : "POST",
            url  : base_url + "admin/colloque/removeoption",
            data : { id: price, _token: $("meta[name='_token']").attr('content') },
            success: function(data) {
                $main.replaceWith(data);
            },
            error: function(){alert('problème avec la suppresion de \'option');}
        });
    });

    /*
    * Inscription form clones
    */
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

    $('#multi-select').multiSelect({
        selectableHeader: "<input type='text' class='form-control' style='margin-bottom: 10px;'  autocomplete='off' placeholder='Rechercher par nom'>",
        selectionHeader : "<input type='text' class='form-control' style='margin-bottom: 10px;' autocomplete='off' placeholder='Rechercher par nom'>",
        afterInit: function(ms){

            var that = this,
                $selectableSearch      = that.$selectableUl.prev(),
                $selectionSearch       = that.$selectionUl.prev(),
                selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
                selectionSearchString  = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

            that.qs1 = $selectableSearch.quicksearch(selectableSearchString).on('keydown', function(e){
                if (e.which === 40){
                    that.$selectableUl.focus();
                    return false;
                }
            });

            that.qs2 = $selectionSearch.quicksearch(selectionSearchString).on('keydown', function(e){
                if (e.which == 40){
                    that.$selectionUl.focus();
                    return false;
                }
            });

        },
        afterSelect: function(){
            this.qs1.cache();
            this.qs2.cache();
        },
        afterDeselect: function(){
            this.qs1.cache();
            this.qs2.cache();
        }
    });


    $('#typeSelect').change(function()
    {
        if($(this).val() == 'product')
        {
            $('#productSelect').show();
        }
        else
        {
            $('#productSelect').hide();
        }
    });

    $('.colorpicker').colorPicker();


    var base_url = location.protocol + "//" + location.host+"/";

    $("#tags").tagit({
        fieldName          : "specialisation",
        placeholderText    : "Rechercher une spécialisation",
        removeConfirmation : true,
        afterTagAdded: function(event, ui) {
            if(!ui.duringInitialization)
            {
                var specialisation = ui.tagLabel;
                var colloque_id    = $(this).data('id');

                $.ajax({
                    dataType : "json",
                    type     : 'POST',
                    url      : base_url + 'admin/specialisation',
                    data: {  colloque_id  : colloque_id, specialisation : specialisation , _token: $("meta[name='_token']").attr('content') },
                    success: function( data ) {
                        console.log('added');
                    },
                    error: function(data) {  console.log('error');  }
                });
            }
        },
        beforeTagRemoved: function(event, ui) {

            var specialisation = ui.tagLabel;
            var colloque_id    = $(this).data('id');

            var answer = confirm('Voulez-vous vraiment supprimer : '+ specialisation +' ?');
            if (answer) {
                $.ajax({
                    dataType : "json",
                    type     : 'POST',
                    url      : base_url + 'admin/specialisation/destroy',
                    data     : {_method: 'delete', colloque_id: colloque_id, specialisation: specialisation, _token: $("meta[name='_token']").attr('content')},
                    success: function (data) {
                        console.log('removed');
                    },
                    error: function (data) {console.log('error');}
                });
            }
            else
            {
                return false;
            }
        },
        autocomplete: {
            delay: 0,
            minLength: 2,
            source: function( request, response ) {
                $.ajax({
                    dataType : "json",
                    type     : 'GET',
                    url      : base_url + 'admin/specialisation/search',
                    data: {  term: request.term , _token: $("meta[name='_token']").attr('content') },
                    success: function( data ) {
                        response( $.map( data, function( item ) {
                            return {
                                label: item.label,
                                value: item.label
                            }
                        }));
                    },
                    error: function(data) {  console.log('error');  }
                });
            }
        }
    });

});