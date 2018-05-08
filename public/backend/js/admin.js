$( function() {

    var base_url = location.protocol + "//" + location.host+"/";

    $('[data-toggle="popover"]').popover({
        html : true,
        trigger : 'hover'
    });

    $('.colorpicker').colorPicker();

    $('#newpassword').val('');

    $("#flUpload").change(function ()
    {
        $("#lblSize").empty().hide();

        var iSize = ($("#flUpload")[0].files[0].size / 1024);

        if(iSize > 1500){
            $("#lblSize").html("L'image est trop lourde, merci de prendre un image de moins de 1.5Mb").show();
        }
    });

    var $excelGroup = $('#excelGroup');
    $excelGroup.on('show.bs.collapse','.collapse', function() {
        $excelGroup.find('.collapse.in').collapse('hide');
    });

    var ordersList    = $('#ordersList');
    var selectStatus  = $('.order-filter-status');

    if(ordersList.length) {
        selectStatus.val('');
        selectStatus.change(function() { orderFilter(); });
    }

    function orderFilter()
    {
        var status = selectStatus.val();

        if(status){
            console.log('status:' + status);
            $('tr.mainRow').addClass('hidden');
            $('tr[data-status="' + status + '"]').removeClass('hidden');
        }
        else{
            $('tr.mainRow').removeClass('hidden');
        }
    }

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

    $('.select-chosen').chosen();

    $("#printSondage").on('click', function() {
        $("#sondagePrintDiv").print({
            globalStyles: true,
            mediaPrint: true,
            stylesheet: null,
            noPrintSelector: ".no-print",
            iframe: true,
            append: null,
            prepend: null,
            manuallyCopyFormValues: true,
            deferred: $.Deferred(),
            timeout: 750,
            title: null,
            doctype: '<!doctype html>'
        });
    });


    /*
     * delete action confirmation
     * */
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

    $('body').on('click','#email_btn',function(event){
        event.preventDefault();
        event.stopPropagation();

        var $input  = $('#email_input');

        var hash = Math.random().toString(36).substr(2, 12);
        hash = hash + '@publications-droit.ch';

        $input.val(hash);
        $('#email_warning').show();

    });

    /*
    * For product abos
    * */
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

    $('.multi-selection').multiSelect({
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
        var value   = $('#typeSelect').val();
        var $addon  = $('#val_addon');

        if(value == 'product') {
            $select.show();
            $addon.text('%');
        }
        else if(value == 'price' || value == 'priceshipping'){
            $select.show();
            $addon.text('CHF');
        }
        else{
            $select.hide();
            $addon.text('%');
        }
    }

    isProductCoupon();

    $('#typeSelect').change(function() { isProductCoupon(); });

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

    $(".mask_tel").mask('000 000 00 00');

    $('body').on('click','.mask_international',function () {
        $('.mask_tel').unmask();
        $('.mask_international').hide();
        $('.mask_suisse').show();
    });

    $('body').on('click','.mask_suisse',function () {
        $(".mask_tel").mask('000 000 00 00');
        $('.mask_suisse').hide();
        $('.mask_international').show();
    });

    $("#editForm").submit(function (e) {
        $inputs.each( function( index, element ){
            var intlNumber = $(this).intlTelInput("getNumber");
            if(intlNumber != ''){
                $(this).val(intlNumber);
            }
        });
    });

    // Emails modal
    $('#emailModal').on('show.bs.modal', function (event) {
        var target = $(event.relatedTarget)
        var id     = target.data('id');
        var modal  = $(this)

        modal.find('.modal-title').empty();
        modal.find('.modal-body').empty();

        $.get( base_url + "admin/email/" + id , function( data ) {
            modal.find('.modal-title').text(data.subject);
            modal.find('.modal-body').empty().html(data.body);
        });
    })

    $( "#register" ).validate({
        rules: {
            password: "required",
            first_name: "required",
            last_name: "required",
            email: {
                required: true,  email: true,
                remote: {
                    url: base_url + "check/email",
                    type: "post",
                    data: {
                        email: function() {
                            return $( "#email" ).val();
                        }
                    }
                }
            },
        }
    });

    $('.toggle-presence').change(function() {

        var presence = $(this).prop('checked');
        var id       = $(this).data('id');

        $.ajax({
            type   : "POST",
            url    : base_url + "admin/inscription/presence",
            data   : { presence: presence, id : id,  _token: $("meta[name='_token']").attr('content') },
            success: function(data) {},
            error  : function(){ alert('problème'); }
        });
    });

    /**
     * Sondage select change
     */

     $('body').on('change','#sondageTypeSelect',function(){

         var optionSelected = $("option:selected", this);
         var valueSelected  = optionSelected.val();

         if(valueSelected == 'text' || valueSelected == 'chapitre') {
             $('#sondageChoices').hide();
         }
         else{
             $('#sondageChoices').show();
         }
     });

     // Delete subject seminiaire annexes links
     if($('.annexes_delete').length){

         $('body').on('click','.annexes_delete_btn',function () {
             var id   = $(this).data('id');
             var link = $(this).data('link');

             var $wrapper = $('#delete_annexe_' + id);

             console.log($wrapper);
             console.log(link);

             $.ajax({
                 type   : "POST",
                 url    : base_url + "admin/subject/annexe",
                 data   : { link: link, id : id,  _token: $("meta[name='_token']").attr('content') },
                 success: function(data) {
                     $wrapper.empty().html(data);
                 },
                 error  : function(){ alert('problème'); }
             });

         });
     }

    $('body').on('click','.link_delete_btn',function () {
        var id   = $(this).data('id');
        var $wrapper = $('#download_link');

        $.ajax({
            type   : "POST",
            url    : base_url + "admin/ajax/product/remove_link",
            data   : { id : id,  _token: $("meta[name='_token']").attr('content') },
            success: function(data) {
                $wrapper.empty().html(data);
            },
            error  : function(){ alert('problème'); }
        });
    });

});

$(document).ready(ajustamodal);
$(window).resize(ajustamodal);
function ajustamodal() {
    var altura = $(window).height() - 255; //value corresponding to the modal heading + footer
    $(".ativa-scroll").css({"height":altura,"overflow-y":"auto"});
}
