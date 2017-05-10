
// The url to the application
var base_url = location.protocol + "//" + location.host+"/";

/*
 * Select user
 * Used in inscription
 * */

$( "#searchUser" ).autocomplete({
    appendTo  : "#searchUserWrapper",
    source    : base_url + 'admin/search',
    minLength : 3,
    search    : function (event, ui) {
        var $addon =  $('#searchUser').prev('.input-group-addon');
        $addon.html('<img style="width: 20px; height: 18px;" src="' + base_url + '/images/default.svg" />');
    },
    select: function( event, ui )
    {
        var data = ui.item.adresse;

        $('#inputUser').html('<input type="hidden" value="' + ui.item.value + '" name="user_id">');

        var html = templating(data);

        $('#choiceUser').html(html);

        return false;
    }
}).autocomplete( "instance" )._renderItem = function( ul, item ) {

    $('#searchUser').prev('.input-group-addon').html('');
    return $("<li>").append("<a>" + item.label + "<span>" + item.desc + "</span><span>" + item.company + "</span></a>").appendTo(ul);
};

// Prepare tmeplate for adresse
function templating(data)
{
    var company  = data.company ? data.company + '<br/>' : '';
    var cp       = data.cp ? data.cp + '<br/>' : '';
    var compl    = data.complement ? data.complement + '<br/>' : '';
    var civilite = data.civilite ? data.civilite.title : '';

    var html = '<p><a id="removeUser" class="btn btn-danger btn-xs">Changer</a></p>'
        + '<address>'
        +  company
        +  civilite + ' '
        +  data.first_name + ' ' + data.last_name + '<br/>'
        +  data.adresse + '<br/>' +  compl + cp
        +  data.npa + ' ' + data.ville
        + '</address>';

    return html;
}

// Get already choosen data
// Ex:  Back if validation fails
var $inputUser = $('#inputUser');

if($inputUser.length)
{
    var user = $inputUser.data('user');
    if(user)
    {
        $.get( "admin/user/getUser/" + user , function( data ) {
            console.log(data);
            $('#inputUser').html('<input type="hidden" value="' + data.id + '" name="user_id">');

            $('#choiceUser').html(templating(data.adresse));
        });
    }
}

// Remove the choosen adresse
$('body').on('click','#removeUser', function(e) {
    e.preventDefault();
    $('#inputUser').html('');
    $('#choiceUser').html('');
    $('#adresseFind').addClass('in');
});

/*
* Colloques interactivity
* */

$("#colloqueSelection").chosen();

$.validator.setDefaults({ ignore: ":hidden:not(select)" })
$("#formInscription").validate({
    rules: {
        chosen:"required",
        user_id:"required"
    },
    message: {
        chosen:"Choisir",
        user_id:"Choisir"
    }
});

$( "#colloqueSelection" ).change(function()
{
    var optionSelected = $("option:selected", this);
    var valueSelected  = optionSelected.val();
    var textSelected   = optionSelected.text();

    $("#inputColloque").html('<input type="hidden" value="' + valueSelected + '" name="colloque_id">');
    $("#choiceColloque").html('<h4><strong>Colloque:</strong></h4><p>' + textSelected + '</p>');
});