$( function() {

    var base_url = location.protocol + "//" + location.host+"/";

    $('[data-toggle="popover"]').popover({
        html : true,
        trigger : 'hover'
    });

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
        maxHeight: 270,
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
        $prev.find(".fa-order").removeClass("fa-arrow-circle-right").addClass("fa-arrow-circle-down");
    });

    $('.customCollapse').on('hidden.bs.collapse', function () {
        var $row = $(this).closest('tr');
        var $prev = $row.prev();
        $prev.find(".fa-order").removeClass("fa-arrow-circle-down").addClass("fa-arrow-circle-right");
    });

    $('.collapseArchive').on('show.bs.collapse', function () {
        $('.collapseArchive.in').collapse('hide');
    });


    $("#selectPays").change(function() {
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
    * Select columns for export
    * */

    $('#select_all').on('click',function(){
        var checked = this.checked ? true : false;
        $('.checkbox_all').each(function(){ this.checked = checked; });
    });


    /*
     * Custom toggle btn div
     * */

    var $adresse = $('#adresseParent');

    $adresse.on('show','.collapse', function() {
        $adresse.find('.collapse.in').collapse('hide');
    });

    $('#adresseParent').find('.accordion-toggle').click(function()
    {
        var $toggle = $(this).data('toggle');
        //Expand or collapse this panel
        $('#'+$toggle).slideToggle('fast');
        //Hide the other panels
        $(".collapse.in").not( $('#'+$toggle) ).removeClass('in');
        $(".collapse").not( $('#'+$toggle) ).slideUp('fast');
    });

    $('input[name="intervaltype"]').click(function () {
        //jQuery handles UI toggling correctly when we apply "data-target" attributes and call .tab('show')
        //on the <li> elements' immediate children, e.g the <label> elements:
        $(this).closest('label').tab('show');
    });

});