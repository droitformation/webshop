
// The url to the application
var base_url = location.protocol + "//" + location.host+"/";

$('input.search-adresse').each(function()
{
    var idAutocomplete = $(this).prop("id");
    var $wrapper       = $(this).closest('.autocomplete-wrapper');

    var $input  = $wrapper.find('.input-adresse');
    var $choice = $wrapper.find('.choice-adresse');
    var $find   = $wrapper.find('.adresse-find');

    var adresse = $input.data('adresse');
    var type    = $input.data('type');

    console.log(type);

    if(adresse)
    {
        $.get( "admin/adresse/" + adresse , function( data )
        {
            $choice.html(template(data));
            $find.removeClass('in');
            $input.find('input').val(adresse);
        });
    }

    $( "#" + idAutocomplete ).autocomplete({
        source    : base_url + 'admin/search/adresse',
        minLength : 3,
        select    : function( event, ui )
        {
             var data = ui.item.adresse;

             $input.html('<input type="hidden" value="' + data.id + '" name="' + type + '">');

             var html = template(data);
             $choice.html(html);

             $find.removeClass('in');

            //$(this).val('');
            return false;
        }
    }).autocomplete( "instance" )._renderItem = function( ul, item ){ return $("<li>").append("<a>" + item.label + "<span>" + item.desc + "</span></a>").appendTo(ul); };

});

function template(data)
{
    var company = data.company ? data.company + '<br/>' : '';
    var cp      = data.cp ? data.cp + '<br/>' : '';
    var compl   = data.complement ? data.complement + '<br/>' : '';

    var html = '<p><a class="btn btn-danger btn-xs remove-adresse">Changer</a></p>'
             + '<address>'
             +  company
             +  data.civilite.title + ' '
             +  data.first_name + ' ' + data.last_name + '<br/>'
             +  data.adresse + '<br/>' +  compl + cp
             +  data.npa + ' ' + data.ville
             + '</address>';

    return html;
}

$('body').on('click','.remove-adresse', function(e) {
    e.preventDefault();

    var $wrapper = $(this).closest('.autocomplete-wrapper');

    $wrapper.find('.input-adresse, .choice-adresse').html('');
    $wrapper.find('.adresse-find').addClass('in');
});

/*
 * User interactivity
 * */
/*
$( "#searchUserSimple" ).autocomplete({
    source    : base_url + 'admin/search/adresse',
    minLength : 3,
    select    : function( event, ui )
    {
        displayAdresse(ui.item.adresse);

        $('#adresseFind').removeClass('in');

        //$(this).val('');
        return false;
    }
}).autocomplete( "instance" )._renderItem = function( ul, item ){ return $("<li>").append("<a>" + item.label + "<span>" + item.desc + "</span></a>").appendTo(ul); };

$('body').on('click','#removeUserSimple', function(e) {
    e.preventDefault();
    $('#inputUserSimple, #choiceUserSimple').html('');

    $('#adresseFind').addClass('in');
});

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
*/
