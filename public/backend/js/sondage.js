
$(function() {

    var url  = location.protocol + "//" + location.host+"/";

    $('body').on('click','#sendTestSondage',function(event){
        event.stopPropagation();
        event.preventDefault();

        var id  = $(this).data('id');

        bootbox.prompt({
            title: "Envoyer à",
            inputType: 'email',
            callback: function (result) {

                if(result){
                    $.ajax({
                        type: "POST",
                        data: {'sondage_id' : id, 'email' : result, 'isTest' : true, '_token' : $("meta[name='_token']").attr('content')},
                        url : base_url + "admin/sondage/send",
                        success: function(data) {
                            console.log(data);
                            $('#flashAlert').remove();
                            var alert = '<div id="flashAlert" class="alert alert-success alert-dismissible fade in" role="alert">' +
                                '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                                'Le lien vers le sondage a été envoyé' +
                                '</div>';
                            $('#mainContainer').prepend(alert);
                        },
                        error: function(){ console.log('problème'); }
                    });
                }
                else{
                    bootbox.hideAll();
                }
            }
        });
    });

});