
// The url to the application
var base_url   = location.protocol + "//" + location.host+"/";

/*
 * User interactivity
 * */
$( "#searchUserSimple" ).autocomplete({
    source: base_url + 'admin/search/adresse',
    minLength: 3,
    select: function( event, ui )
    {
        displayAdresse(ui.item.adresse);

        $('#adresseFind').removeClass('in');

        $(this).val('');
        return false;
    }
}).autocomplete( "instance" )._renderItem = function( ul, item ){ return $("<li>").append("<a>" + item.label + "<span>" + item.desc + "</span></a>").appendTo(ul); };

$('body').on('click','#removeUserSimple', function(e) {
    e.preventDefault();
    $('#inputUserSimple, #choiceUserSimple').html('');

    $('#adresseFind').addClass('in');
})

if($('#inputUserSimple').length)
{
    var user = $('#inputUserSimple').data('user');

    if(user)
    {
        $.get( "admin/adresse/" + user , function( data )
        {
            displayAdresse(data);
        });
    }
}

function displayAdresse(data)
{
    $('#inputUserSimple').html('<input type="hidden" value="' + data.id + '" name="adresse_id">');

    var company = data.company ? data.company + '<br/>' : '';
    var cp      = data.cp ? data.cp + '<br/>' : '';
    var compl   = data.complement ? data.complement + '<br/>' : '';

    $('#choiceUserSimple').html(
        '<p><a id="removeUserSimple" class="btn btn-danger btn-xs">Changer</a></p>'
        + '<address>'
        +  company
        +  data.civilite.title + ' '
        +  data.first_name + ' ' + data.last_name + '<br/>'
        +  data.adresse + '<br/>' +  compl + cp
        +  data.npa + ' ' + data.ville
        + '</address>'
    );
}
