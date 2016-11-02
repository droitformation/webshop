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
                        },
                        _token: $("meta[name='_token']").attr('content')
                    }
                }
            },
        }
    });

    $( "#registeraccount" ).validate({
        rules: {
            first_name : "required",
            last_name  : "required",
            adresse    : "required",
            ville      : "required",
            npa        : "required",
            password   : "required",
            password_confirmation: {equalTo: "#password"},
            email: {
                required: true,
                email   : true,
                remote  : {
                    url : base_url + "check/email",
                    type: "post",
                    data: {
                        email: function() {
                            return $( "#email" ).val();
                        },
                        _token: $("meta[name='_token']").attr('content')
                    }
                }
            },
        },
        submitHandler: function(form) {
            
            var first_name = $(form).find("input[name='first_name']").val();
            var last_name  = $(form).find("input[name='last_name']").val();

            $.ajax({
                type   : "POST",
                url    : base_url + "check/name",
                data   : { first_name: first_name, last_name : last_name, _token: $("meta[name='_token']").attr('content') },
                success: function(data) {
                    if(data != 'ok')
                    {
                        console.log(data);

                        bootbox.confirm({
                            title: "Uho",
                            message: data,
                            buttons: {
                                cancel : { label: '<i class="fa fa-times"></i> Fermer'},
                                confirm: { label: '<i class="fa fa-check"></i> Non je suis nouveau'}
                            },
                            callback: function (result) {
                                form.submit();
                            }
                        });

                    }
                },
                error  : function(){}
            });
        }
    });


    $( "#subscribe" ).validate({
        rules: {
            email: {
                required: true,
                email: true
            }
        },
        errorElement : 'div',
        errorPlacement: function(error, element)
        {
            if (element.is(":text"))
            {
                error.prependTo(element.closest('.form'));
            }
            else
            { // This is the default behavior of the script for all fields
                error.insertAfter(element);
            }
        }
    });


    var $billing = $("#billing");

    if($billing.length){

        $billing.validate({
            focusInvalid: false,
            rules: {
                first_name: "required", last_name: "required", adresse: "required", ville: "required", npa: "required",
                email: {
                    required: true,
                    email: true
                }
            }
        }).form();

        $billing.validate({
            focusInvalid: false,
            rules: {
                first_name: "required", last_name: "required", adresse: "required", ville: "required", npa: "required",
                email: {
                    required: true,
                    email: true,
                    remote: {
                        url: base_url + "check/email",
                        type: "post",
                        data: {
                            email: function() {
                                return $( "#email" ).val();
                            },
                            _token: $("meta[name='_token']").attr('content')
                        }
                    }
                }
            }
        });
    }

    $( "#colloque-inscription" ).validate({
        errorElement : 'div',
        errorPlacement: function(error, element)
        {
            if (element.is(":radio") || element.is(":checkbox")) {
                error.prependTo(element.parent());
            } else { // This is the default behavior of the script for all fields
                error.insertAfter(element);
            }
        }
    });
});

