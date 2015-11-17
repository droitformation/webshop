
// The url to the application
var base_url = location.protocol + "//" + location.host+"/";

$( "#searchUser" ).autocomplete({
    source: base_url + 'admin/search',
    minLength: 3,
    select: function( event, ui )
    {
        $('#inputUser').html('<input type="hidden" value="' + ui.item.value + '" name="user_id">');

        var company = ui.item.adresse.company ? ui.item.adresse.company + '<br/>' : '';
        var cp      = ui.item.cp ? ui.item.cp + '<br/>' : '';
        var compl   = ui.item.adresse.complement ? ui.item.adresse.complement + '<br/>' : '';

        $('#choiceUser').html(
            '<h4><strong>Utilisateur</strong></h4>'
            + '<p><a id="removeUser" class="btn btn-danger btn-xs">Retirer</a></p>'
            + '<address>'
            +  ui.item.adresse.civilite.title + '<br/>'
            +  company
            +  ui.item.label + '<br/>'
            +  ui.item.adresse.adresse + '<br/>'
            +  compl + cp
            +  ui.item.adresse.npa + ' ' + ui.item.adresse.ville
            + '</address>'
        );

        $(this).val('');

        return false;
    }
}).autocomplete( "instance" )._renderItem = function( ul, item ) {
    return $("<li>").append("<a>" + item.label + "<span>" + item.desc + "</span></a>").appendTo(ul);
};

$('body').on('click','#removeUser', function(e) {
    e.preventDefault();
    $('#inputUser').html('');
    $('#choiceUser').html('');
});

$( "#colloqueSelection" ).change(function()
{
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
        error: function(){alert('problème avec la séléction de l\'utilisateur');}
    });
});


/*
 * Product add to new order
 * */

$('.chosen-select').chosen();

$('body').on("click", '#cloneBtnOrder' ,function(e) {

    var $wrapper_clone  = $('#wrapper_clone_order');
    var $fieldset_clone = $('#fieldset_clone_order');

    e.preventDefault();
    e.stopPropagation();

    var clone = $fieldset_clone.clone();
    clone.find('input').removeAttr('value');
    clone.find('input[type="checkbox"]').attr('checked', false);
    var select = clone.find('.chosen-select');

    $(select).removeClass("chzn-done").removeAttr("id").css("display", "block").next().remove();

    clone.attr('id', '');
    clone.appendTo($wrapper_clone);

    select.chosen();

});

$('body').on("click", '.remove_order' ,function(e) {
    e.preventDefault(); e.stopPropagation();

    $(this).closest('fieldset').remove();
});