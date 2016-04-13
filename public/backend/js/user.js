
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

  /*  if(adresse)
    {
        $.get( "admin/adresse/" + adresse , function( data )
        {
            $choice.html(template(data));
            $find.removeClass('in');
            $input.find('input').val(adresse);
        });
    }*/

    $( "#" + idAutocomplete ).autocomplete({
        source    : base_url + 'admin/search/adresse',
        minLength : 3,
        select    : function( event, ui )
        {
             var data = ui.item.adresse;

             $input.html('<input type="hidden" value="' + ui.item.value + '" name="' + ui.item.type + '"><input type="hidden" value="' + ui.item.type + '" name="type">');

             var html = template(data, type);

             $choice.html(html);
             $find.removeClass('in');

             return false;
        }
    }).autocomplete( "instance" )._renderItem = function( ul, item ){
        return $("<li>").append("<a>" + item.label + "<span>" + item.desc + "</span></a>").appendTo(ul);
    };

});

function template(data, type)
{
    var company  = data.company ? data.company + '<br/>' : '';
    var cp       = data.cp ? data.cp + '<br/>' : '';
    var compl    = data.complement ? data.complement + '<br/>' : '';
    var civilite = data.civilite ? data.civilite.title : '';

    var html = '<p><a class="btn btn-danger btn-xs remove-adresse">Changer</a></p>'
             + '<address>'
             +  company
             +  civilite + ' '
             +  data.first_name + ' ' + data.last_name + '<br/>'
             +  data.adresse + '<br/>' +  compl + cp
             +  data.npa + ' ' + data.ville
             + '</address>';

    return html;
}

var $search = $('.input-adresse');

if($search.length)
{
    var $wrapper = $search.closest('.autocomplete-wrapper');
    var $choice  = $wrapper.find('.choice-adresse');

    var uid  = $search.data('uid');
    var type = $search.data('type');

    var res  = type.replace("_id", "");

    console.log('admin/' + res + '/getAdresse/' + uid);

    if(uid)
    {
        $.get( 'admin/' + res + '/getAdresse/' + uid , function( data )
        {
            console.log(data);
            $choice.html(template(data));
            $search.html('<input type="hidden" value="' + uid + '" name="' + type + '"><input type="hidden" value="' + type + '" name="type">');
        });
    }
}


$('body').on('click','.remove-adresse', function(e) {
    e.preventDefault();

    var $wrapper = $(this).closest('.autocomplete-wrapper');

    $wrapper.find('.input-adresse, .choice-adresse').html('');
    $wrapper.find('.adresse-find').addClass('in');

    var $input = $wrapper.find('.input-adresse');
    var type   = $input.data('type');

    $input.html('<input type="hidden" value="" name="' + type + '">');

});
