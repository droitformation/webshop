
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

                console.log(result);

                $.ajax({
                    type: "POST",
                    date: {'sondage_id' : id, 'email' : result, '_token' : $("meta[name='_token']").attr('content')},
                    url : base_url + "admin/sondage/send",
                    success: function(data) {
                        console.log(data);
                    },
                    error: function(){ console.log('problème'); }
                });

            }
        });
    });

});