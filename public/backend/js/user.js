
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

    $( "#" + idAutocomplete ).autocomplete({
        source    : base_url + 'admin/search/adresse',
        minLength : 3,
        select    : function( event, ui )
        {
             var data = ui.item.adresse;

             var uid  = (type == 'adresse_id') || (type == 'tiers_id') ? data.id : ui.item.value;
             var name = (type == 'adresse_id') || (type == 'tiers_id') ? type : 'user_id';

             $input.html('<input type="hidden" value="' + uid + '" name="' + name + '"><input type="hidden" value="' + name + '" name="type">');

             var html = template(data, ui.item.type);

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

if($search.length && $(this).data('uid'))
{
    $search.each(function()
    {
        var $wrapper = $(this).closest('.autocomplete-wrapper');
        var $choice  = $wrapper.find('.choice-adresse');

        var uid  = $(this).data('uid');
        var type = $(this).data('type');
        var name = type == 'adresse_id' || 'tiers_id' ? 'adresse_id' : 'user_id';
        var res  = name.replace("_id", "");

        if(uid)
        {
            $.get( 'admin/' + res + '/getAdresse/' + uid , function( data )
            {
                $choice.html(template(data));
                $(this).html('<input type="hidden" value="' + uid + '" name="' + name + '"><input type="hidden" value="' + name + '" name="type">');
                $wrapper.find('.adresse-find').removeClass('in');
            });
        }
    });
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
