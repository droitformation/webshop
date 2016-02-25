
// The url to the application
var base_url = location.protocol + "//" + location.host+"/";

/*
* User interactivity
* */
$( "#searchUser" ).autocomplete({
    appendTo  : "#searchUserWrapper",
    source: base_url + 'admin/search',
    minLength: 3,
    search    : function (event, ui) {
        var $addon =  $('#searchUser').prev('.input-group-addon');
        $addon.html('<img style="width: 20px; height: 18px;" src="' + base_url + '/images/default.svg" />');
        console.log($addon);
    },
    select: function( event, ui )
    {
        $('#inputUser').html('<input type="hidden" value="' + ui.item.value + '" name="user_id">');
        $(this).val('');

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

        return false;
    }
}).autocomplete( "instance" )._renderItem = function( ul, item ) {

    var $addon =  $('#searchUser').prev('.input-group-addon').html('');

    return $("<li>").append("<a>" + item.label + "<span>" + item.desc + "</span></a>").appendTo(ul);
};

$('body').on('click','#removeUser', function(e) {
    e.preventDefault();
    $('#inputUser').html('');
    $('#choiceUser').html('');
    $('#adresseFind').addClass('in');
})

var $inputUser = $('#inputUser');

if($inputUser.length)
{
    var user = $inputUser.data('user');
    if(user)
    {
        $.get( "admin/user/" + user , function( data ) {
            console.log(data);
            $('#inputUser').html('<input type="hidden" value="' + data.id + '" name="user_id">');

            var company = data.company ? data.company + '<br/>' : '';
            var cp      = data.cp ? data.cp + '<br/>' : '';
            var compl   = data.complement ? data.complement + '<br/>' : '';

            $('#choiceUser').html(
                '<p><a id="removeUser" class="btn btn-danger btn-xs">Retirer</a></p>'
                + '<address>'
                +  company
                +  data.civilite.title + ' '
                +  data.first_name + ' ' + data.last_name + '<br/>'
                +  data.adresse + '<br/>'
                +  compl + cp
                +  data.npa + ' ' + data.ville
                + '</address>'
            );

        });
    }
}

/*
* Colloques interactivity
* */

$( "#colloqueSelection" ).change(function()
{
    var optionSelected = $("option:selected", this);
    var valueSelected  = optionSelected.val();
    var textSelected   = optionSelected.text();

    $("#inputColloque").html('<input type="hidden" value="' + valueSelected + '" name="colloque_id">');
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