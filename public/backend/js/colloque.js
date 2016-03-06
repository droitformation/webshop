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
        url : base_url + "admin/location/" + id,
        success: function(data) {
            if(data)
            {
                var map = (data.map ? '<img style="width:100%;" src="files/colloques/cartes/'+ data.map +'" alt="Map"><small>La carte du bon</small>' : '<span style="display: block;" class="text-danger">il n\'existe pas de carte</span>');
                var html = '<div class="thumbnail thumbnail-colloque"><div class="row"><div class="col-md-3">'
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
        url : base_url + "admin/organisateur/" + id,
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
            $('.editablePrice').editable({
                emptytext : '',
                params: function(params) {
                    params._token = $("meta[name='_token']").attr('content');
                    return params;
                }
            });
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
            $('.editablePrice').editable({
                emptytext : '',
                params: function(params) {
                    params._token = $("meta[name='_token']").attr('content');
                    return params;
                }
            });
        },
        error: function(){alert('problème avec la suppresion du prix');}
    });
});

$('body').on("click",'.addOption',function(e) {

    e.preventDefault();
    e.stopPropagation();

    var $form = $(this).closest('div.option');
    var $main = $(this).closest('.form-group');
    var data  = $form.find("select,textarea,input").serialize();

    $.ajax({
        type : "POST",
        url  : base_url + "admin/colloque/addoption",
        data : { data: data, _token: $("meta[name='_token']").attr('content') },
        success: function(data) {
            $main.replaceWith(data);
            $('.editableOption').editable({
                params: function(params) {
                    // add additional params from data-attributes of trigger element
                    params.model  = $(this).editable().data('model');
                    params._token = $("meta[name='_token']").attr('content');
                    return params;
                }
            });
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
            $('.editableOption').editable({
                params: function(params) {
                    // add additional params from data-attributes of trigger element
                    params.model  = $(this).editable().data('model');
                    params._token = $("meta[name='_token']").attr('content');
                    return params;
                }
            });
        },
        error: function(){alert('problème avec la suppresion de \'option');}
    });
});

$('.editableOption').editable({
    params: function(params) {
        // add additional params from data-attributes of trigger element
        params.model = $(this).editable().data('model');
        params._token = $("meta[name='_token']").attr('content');
        return params;
    }
});

$('.editablePrice').editable({
    emptytext : '',
    params: function(params) {
        params._token = $("meta[name='_token']").attr('content');
        return params;
    }
});

$('.editablePayementDate').editable({
    emptytext : '',
    params: function(params) {
        // add additional params from data-attributes of trigger element
        params.model  = $(this).editable().data('model');
        params._token = $("meta[name='_token']").attr('content');
        return params;
    },
    success: function(response, newValue)
    {
        var $input = $(this).closest('.input-group').find('.input-group-addon').text(response.etat);
        $input.removeClass('bg-default bg-success');
        $input.addClass('bg-'+ response.color);

        if(!response.success)
        {
            return response.msg;
        }
    }
});

$(document).on('change', '#selectTypeOption', function (e){

    var $type         = $(this).val();
    var $optionGroupe = $('#optionGroupe');
    if($type == 'choix')
    {
        $optionGroupe.show();
    }

});