
$(function() {

    var url  = location.protocol + "//" + location.host+"/";

    $('body').on('click','#confirmSendRappels',function(event){
        event.stopPropagation();
        event.preventDefault();

        bootbox.prompt({
            title: "This is a prompt with an email input!",
            inputType: 'email',
            callback: function (result) {
                console.log(result);
                $('#sendRappelForm').submit();
            }
        });
    });

});