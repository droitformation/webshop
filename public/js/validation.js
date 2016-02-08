$(function(){

    var base_url = location.protocol + "//" + location.host+"/";

    $( "#login" ).validate({
        rules: {
            password: "required",
            password_confirmation: {
                equalTo: "#password"
            },
            email: {
                required: true,
                email: true,
                remote: {
                    url: base_url + "check/email",
                    type: "post",
                    data: {
                        email: function() {
                            return $( "#email" ).val();
                        }
                    }
                }
            },
        }
    });

});

