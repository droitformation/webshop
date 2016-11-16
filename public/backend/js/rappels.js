/*****
 * Rappel confirmation modal
 * */

var url  = location.protocol + "//" + location.host+"/";

$('body').on('click','#select_all',function(){
    var checked = this.checked ? true : false;
    $('.checkbox_all input.rappel-input').each(function(){
        this.checked = checked;
    });
});

$('body').on('click','#confirmSendRappels',function(event){
    event.stopPropagation();event.preventDefault();

    var $form  = $(this).closest('form');
    var id     = $form.find("input[name='colloque_id']").val();

    $.ajax({
        type: "GET",
        url : base_url + "admin/inscription/rappels/" + id,
        success: function(result) {

            var $list = [];

            $.each(result,function(index, value) {
                $list.push('<p><input type="checkbox" checked class="rappel-input" name="inscriptions[]" value="'+ value.id +'" />'+ value.name +' : '+ value.inscription_no + '</p>' );
            });

            var $html = '<form id="sendRappelForm" action="' + url + 'admin/inscription/rappel/send" method="POST">'
                        + '<input type="hidden" name="_token" value="' + $("meta[name='_token']").attr('content') + '">'
                        + '<p><input id="select_all" type="checkbox"> &nbsp;Tout cocher/décocher</p>'
                        + '<div class="checkbox_all">'
                        + $list.join('')
                        + '</div></form>';

            bootbox.confirm({
                message: '<h4>Voulez-vous vraiment envoyer les rappels suivants?</h4>' + $html,
                buttons: {
                    confirm: { label: 'Oui', className: 'btn-success'},
                    cancel : { label: 'Non', className: 'btn-danger'}
                },
                callback: function (result) {
                    if(result){

                        console.log($('#sendRappelForm'))

                        $('#sendRappelForm').submit();
                    }

                    bootbox.hideAll();
                }
            });
        },
        error: function(){ console.log('problème'); }
    });

});