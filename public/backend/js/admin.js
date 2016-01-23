$( function() {

    var base_url = location.protocol + "//" + location.host+"/";

    $('.redactor').redactor({
        minHeight  : 250,
        maxHeight: 450,
        focus: true,
        lang: 'fr',
        plugins: ['advanced','imagemanager','filemanager'],
        fileUpload : 'admin/uploadFileRedactor?_token=' + $('meta[name="_token"]').attr('content'),
        imageUpload: 'admin/uploadRedactor?_token=' + $('meta[name="_token"]').attr('content'),
        imageManagerJson: 'admin/imageJson',
        fileManagerJson: 'admin/fileJson',
        buttons    : ['html','|','formatting','bold','italic','|','unorderedlist','orderedlist','outdent','indent','|','image','file','link','alignment']
    });

    $('.redactorSimple').redactor({
        minHeight: 50,
        maxHeight: 100,
        focus    : true,
        lang: 'fr',
        buttons  : ['formatting','bold','italic','link','|','unorderedlist']
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
    $('body').on("click",".addPrice",function(e) {

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

    function isProductCoupon()
    {
        var $select = $('#productSelect');

        if($('#typeSelect').val() == 'product')
        {
            $select.show();
        }
        else
        {
            $select.hide();
        }
    }

    isProductCoupon();

    $('#typeSelect').change(function() { isProductCoupon(); });

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

    $('.customCollapse').on('shown.bs.collapse', function () {
        var $row = $(this).closest('tr');
        var $prev = $row.prev();
        $prev.find(".fa").removeClass("fa-arrow-circle-right").addClass("fa-arrow-circle-down");
    });

    $('.customCollapse').on('hidden.bs.collapse', function () {
        var $row = $(this).closest('tr');
        var $prev = $row.prev();
        $prev.find(".fa").removeClass("fa-arrow-circle-down").addClass("fa-arrow-circle-right");
    });


    $( "#selectPays" ).change(function() {
        console.log('sv');
        var optionSelected = $("option:selected", this);
        var valueSelected  = optionSelected.val();

        if(valueSelected != 208)
        {
            $('#selectCantons').hide();
        }
        else{
            $('#selectCantons').show();
        }
    });

    /*
    * checkboxes
    * */

    $('#select_all').on('click',function(){
        if(this.checked)
        {
            $('.checkbox_all').each(function(){
                this.checked = true;
            });
        }
        else
        {
            $('.checkbox_all').each(function(){
                this.checked = false;
            });
        }
    });

/*    $('.checkbox').on('click',function(){
        if($('.checkbox:checked').length == $('.checkbox_all').length){
            $('#select_all').prop('checked',true);o
        }else{
            $('#select_all').prop('checked',false);
        }
    });*/

    var $adresse = $('#adresseParent');

    $adresse.on('show','.collapse', function() {
        console.log($(this));
        $adresse.find('.collapse.in').collapse('hide');
    });

    $('#adresseParent').find('.accordion-toggle').click(function()
    {
        var $toggle = $(this).data('toggle');

        console.log($toggle);
        //Expand or collapse this panel
        $('#'+$toggle).slideToggle('fast');
        //Hide the other panels
        $(".collapse").not( $('#'+$toggle) ).slideUp('fast');
    });

    $('#selectAbos').ddslick({
        selectText: "Select your favorite social network",
        onSelected: function (data) {
            console.log(data);
        }
    });

    var fuzzyOptions = {
        searchClass: "fuzzy-search",
        location: 0,
        distance: 100,
        threshold: 0.4,
        multiSearch: true
    };
    var options = {
        valueNames: [ 'title', 'ISBN', 'author','Référence', 'Éditeur', 'domain', 'categorie' ],
        page: 10,
        plugins: [
            ListFuzzySearch(),
            ListPagination({})
        ]
    };

    var userList = new List('search-list', options);

    /*
     Inline edit for prices and options colloques
    */

    $('.editableOption').editable();
    $('.editablePrice').editable();

});