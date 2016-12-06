
/********************************************
 * Payement inscriptions
 * For colloque
 *******************************************/

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
        $input.removeClass('bg-default bg-success bg-info');
        $input.addClass('bg-'+ response.color);

        return !response.success ? response.msg : null;
    }
});

/********************************************
 * Prices
 * For colloque
 *******************************************/
$('body').on("click",".addPrice",function(e) {

    e.preventDefault();e.stopPropagation();

    var $form = $(this).closest('div.price');
    var $main = $(this).closest('.form-group');
    var data  = $form.find("select,textarea,input").serialize();

    $.ajax({
        type : "POST",
        url  : base_url + "admin/price",
        data : { data: data, _token: $("meta[name='_token']").attr('content') },
        success: function(data) {
            $main.find('.priceWrapper').empty();
            $main.replaceWith(data);

            $('.editablePrice').editable({
                emptytext : '',
                params: function(params) {
                    params._token   = $("meta[name='_token']").attr('content');
                    params._method  = 'put';
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
        url  : base_url + "admin/price/" + price,
        data : { id: price, _method: 'delete', _token: $("meta[name='_token']").attr('content') },
        success: function(data) {
            $main.replaceWith(data);

            $('.editablePrice').editable({
                emptytext : '',
                params: function(params) {
                    params._token   = $("meta[name='_token']").attr('content');
                    params._method  = 'put';
                    return params;
                }
            });

        },
        error: function(){alert('problème avec la suppression du prix');}
    });
});

$('.editablePrice').editable({
    emptytext : '',
    params: function(params) {
        params._token   = $("meta[name='_token']").attr('content');
        params._method  = 'put';
        return params;
    }
});

/********************************************
 * Option Group
 * For colloque
 *******************************************/

$('body').on("click",'.addGroupBtn',function(e) {
    var group = $(this).data('id');
    $('#addGroupWrapper_' + group).toggle();
});

$('body').on("click",'.addGroup',function(e) {

    e.preventDefault(); e.stopPropagation();

    var group = $(this).data('id');
    var $form = $(this).closest('.addGroupForm');
    var data  = $form.find("input").serialize();
    var $main = $(this).closest('.form-group');

    $.ajax({
        type : "POST",
        url  : base_url + "admin/groupoption",
        data : { data: data, _token: $("meta[name='_token']").attr('content') },
        success: function(data) {
            $main.replaceWith(data);

            $('.editableOption').editable({
                params: function(params) {
                    // add additional params from data-attributes of trigger element
                    params._token   = $("meta[name='_token']").attr('content');
                    params._method  = 'put';
                    return params;
                }
            });

        },
        error: function(){alert('problème avec l\'ajout du groupe');}
    });
});



$('body').on("click",'.removeGroup', function(e) {

    e.preventDefault();e.stopPropagation();

    var group = $(this).data('id');
    var $main = $(this).closest('.form-group');

    $.ajax({
        type : "POST",
        url  : base_url + "admin/groupoption/" + group,
        data : { id: group, _method: 'delete',  _token: $("meta[name='_token']").attr('content') },
        success: function(data) {

            $main.replaceWith(data);

            $('.editableOption').editable({
                params: function(params) {
                    // add additional params from data-attributes of trigger element
                    params._token   = $("meta[name='_token']").attr('content');
                    params._method  = 'put';
                    return params;
                }
            });
        },
        error: function(){alert('problème avec la suppression du choix');}
    });
});



/********************************************
 * Occurrences
 * For colloque
 *******************************************/

$('.editableItem').editable({
    emptytext : '',
    params: function(params) {
        params._token   = $("meta[name='_token']").attr('content');
        params._method = 'put';
        return params;
    }
});

$('.editableItemCheck').editable({
    value: 0,
    source: [{value: 0,text: 'Ouvert au public'}, {value: 1,text: 'Complet'}],
    params: function(params) {
        params._token   = $("meta[name='_token']").attr('content');
        params._method = 'put';
        return params;
    }
});

$('body').on("click",'.addItem',function(e) {

    // Prevent default behavior of anchor
    e.preventDefault();e.stopPropagation();

    // get the main form-group
    var $main = $(this).closest('.form-group');
    // get the main item div wrapper
    var $form = $(this).closest('div.itemWrapper');
    // Get all data from form and serialize
    var data  = $form.find("select,textarea,input").serialize();

    $.ajax({
        type : "POST",
        url  : base_url + "admin/occurrence",
        data : { data: data, _token: $("meta[name='_token']").attr('content') },
        success: function(data)
        {
            $main.replaceWith(data); // replace view with new html

            // editable inplace reattach to dom
            $('.datePicker').datepicker({
                format: 'yyyy-mm-dd',
                language: 'fr'
            });

            $('.editableItem').editable({
                params: function(params) {
                    // add additional params from data-attributes of trigger element
                    params._token  = $("meta[name='_token']").attr('content');
                    params._method = 'put';
                    return params;
                }
            });

            $('.editableItemCheck').editable({
                value: 0,
                source: [{value: 0,text: 'Ouvert au public'}, {value: 1,text: 'Complet'}],
                params: function(params) {
                    params._token   = $("meta[name='_token']").attr('content');
                    params._method = 'put';
                    return params;
                }
            });

        },
        error: function(){alert('problème avec l\'ajout de la conférence');}
    });
});

$('body').on("click",'.removeItem', function(e) {

    // Prevent default behavior of anchor
    e.preventDefault();e.stopPropagation();

    var id    = $(this).data('id');
    var $main = $(this).closest('.form-group');

    $.ajax({
        type : "POST",
        url  : base_url + "admin/occurrence/" + id,
        data : { id: id, _method: 'delete', _token: $("meta[name='_token']").attr('content') },
        success: function(data) {

            $main.replaceWith(data); // replace view with new html

            // editable inplace reattach to dom
            $('.datePicker').datepicker({
                format: 'yyyy-mm-dd',
                language: 'fr'
            });

            $('.editableItem').editable({
                params: function(params) {
                    // add additional params from data-attributes of trigger element
                    params._token  = $("meta[name='_token']").attr('content');
                    params._method = 'put';
                    return params;
                }
            });

            $('.editableItemCheck').editable({
                value: 0,
                source: [{value: 0,text: 'Ouvert au public'}, {value: 1,text: 'Complet'}],
                params: function(params) {
                    params._token   = $("meta[name='_token']").attr('content');
                    params._method = 'put';
                    return params;
                }
            });
        },
        error: function(){alert('problème avec la suppression');}
    });
});


/********************************************
 * Options
 * For colloque
 *******************************************/

$(document).on('change', '#selectTypeOption', function (e){
    var $type         = $(this).val();
    var $optionGroupe = $type == 'choix' ? $('#optionGroupe').show() : $('#optionGroupe').hide();
});


$('.editableOption').editable({
    params: function(params) {
        // add additional params from data-attributes of trigger element
        params._token   = $("meta[name='_token']").attr('content');
        params._method  = 'put';
        return params;
    }
});


$('body').on("click",'.addOption',function(e) {

    e.preventDefault();e.stopPropagation();

    var $form = $(this).closest('div.option');
    var $main = $(this).closest('.form-group');
    var data  = $form.find("select,textarea,input").serialize();

    $.ajax({
        type : "POST",
        url  : base_url + "admin/option",
        data : { data: data, _token: $("meta[name='_token']").attr('content') },
        success: function(data) {
            $main.replaceWith(data);
            $('.editableOption').editable({
                params: function(params) {
                    params._token   = $("meta[name='_token']").attr('content');
                    params._method  = 'put';
                    return params;
                }
            });
        },
        error: function(){alert('problème avec l\'ajout de \'option');}
    });
});


$('body').on("click",'.removeOption', function(e) {

    e.preventDefault();e.stopPropagation();

    var option = $(this).data('id');
    var $main  = $(this).closest('.form-group');

    $.ajax({
        type : "POST",
        url  : base_url + "admin/option/" + option,
        data : { id: option, _method: 'delete', _token: $("meta[name='_token']").attr('content') },
        success: function(data) {
            $main.replaceWith(data);

            $('.editableOption').editable({
                params: function(params) {
                    // add additional params from data-attributes of trigger element
                    params._token   = $("meta[name='_token']").attr('content');
                    params._method  = 'put';
                    return params;
                }
            });

        },
        error: function(){alert('problème avec la suppression de \'option');}
    });
});