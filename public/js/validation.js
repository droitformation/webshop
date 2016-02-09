jQuery(document).ready(function($){

    var base_url = location.protocol + "//" + location.host+"/";

    $( "#login" ).validate({
        rules: {
            password: "required",
            password_confirmation: {equalTo: "#password"},
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

    $( "#billing" ).validate({
        focusInvalid: false,
        rules: {
            first_name: "required",
            last_name: "required",
            adresse: "required",
            ville: "required",
            npa: "required",
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

