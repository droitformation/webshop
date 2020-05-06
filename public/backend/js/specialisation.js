$( function() {

    var base_url = location.protocol + "//" + location.host+"/";

    if($("#specialisations").length)
    {
        var tags = $("#specialisations").data('tags');
        tags = tags ? tags : null;

        $.get(base_url + 'admin/specialisation', function( data )
        {
            $("#specialisations").tagit({
                fieldName          : "specialisation",
                placeholderText    : "Ajouter une spécialisation",
                removeConfirmation : true,
                tagSource          : data,
                afterTagAdded: function(event, ui)
                {
                    if(!ui.duringInitialization)
                    {
                        var specialisation = ui.tagLabel;
                        var id    = $(this).data('id');
                        var model = $(this).data('model');

                        $.ajax({
                            dataType : "json",
                            type     : 'POST',
                            url      : base_url + 'admin/specialisation',
                            data: { id : id, model : model, specialisation : specialisation , _token: $("meta[name='_token']").attr('content') },
                            success: function( data ) {console.log('added');},
                            error: function(data) {  console.log('error');  }
                        });
                    }
                },
                beforeTagRemoved: function(event, ui)
                {
                    var specialisation = ui.tagLabel;
                    var id    = $(this).data('id');
                    var model = $(this).data('model');

                    var answer = confirm('Voulez-vous vraiment supprimer : '+ specialisation +' ?');
                    if (answer) {
                        $.ajax({
                            dataType : "json",
                            type     : 'POST',
                            url      : base_url + 'admin/specialisation/destroy',
                            data     : { _method: 'delete', id: id, model : model, specialisation: specialisation, _token: $("meta[name='_token']").attr('content')},
                            success: function (data) {console.log('removed');},
                            error: function (data) {console.log('error');}
                        });
                    }
                    else { return false; }
                },
                beforeTagAdded: function(event, ui) {
                    if ($.inArray(ui.tagLabel, data) == -1) {return false;}
                    if(tags){
                        if ($.inArray(ui.tagLabel, tags) == -1) {return false;}
                    }
                }
            });
        });
    }

    if($("#rabais_tags").length) {

        let id = $('#rabais').data('id');

        $.get(base_url + 'admin/rabais/all/' + id, function( data ) {

            $("#rabais_tags").tagit({
                fieldName          : "rabais",
                placeholderText    : "Ajouter un rabais",
                removeConfirmation : true,
                tagSource          : data,
                beforeTagAdded: function(event, ui) {
                    let title = ui.tagLabel;
                    let result = false;

                    $.ajax({
                        dataType : "json",
                        type     : 'POST',
                        url      : base_url + 'admin/rabais/has',
                        async:false,
                        data: { id : id, title : title , _token: $("meta[name='_token']").attr('content') },
                        success: function( data ) {
                            console.log(data);

                            if(!data.result){
                                result = true;
                            }
                            else{
                                result = false;
                               // $('#tagused').show()
                               // setTimeout(() =>  $('#tagused').hide(), 4000);
                            }
                        },
                        error: function(data) { return false; }
                    });

                    return result;
                },
                afterTagAdded: function(event, ui) {
                    if(!ui.duringInitialization) {
                        var title = ui.tagLabel;
                        var id     = $('#rabais').data('id');
                        $.ajax({
                            dataType : "json",
                            type     : 'POST',
                            url      : base_url + 'admin/rabais/add',
                            data: { id : id, title : title , _token: $("meta[name='_token']").attr('content') },
                            success: function( data ) {console.log('added');},
                            error: function(data) {  console.log('error');console.log(data);  }
                        });
                    }
                },
                beforeTagRemoved: function(event, ui) {
                    var title = ui.tagLabel;
                    var id    = $('#rabais').data('id');
                    $.ajax({
                        dataType : "json",
                        type     : 'POST',
                        url      : base_url + 'admin/rabais/remove',
                        data     : { id: id, title: title, _token: $("meta[name='_token']").attr('content')},
                        success: function (data) {console.log('removed');},
                        error: function (data) {console.log('error');}
                    });
                }
            });
        });
    }

    if($("#access").length) {
        $.get(base_url + 'admin/specialisation', function( data ) {
            $("#access").tagit({
                fieldName          : "access",
                placeholderText    : "Ajouter un accès",
                removeConfirmation : true,
                tagSource          : data,
                afterTagAdded: function(event, ui) {
                    if(!ui.duringInitialization) {
                        var title = ui.tagLabel;
                        var id     = $(this).data('id');

                        $.ajax({
                            dataType : "json",
                            type     : 'POST',
                            url      : base_url + 'admin/access/add',
                            data: { id : id, title : title , _token: $("meta[name='_token']").attr('content') },
                            success: function( data ) {console.log('added');},
                            error: function(data) {  console.log('error');  }
                        });
                    }
                },
                beforeTagRemoved: function(event, ui) {
                    var title = ui.tagLabel;
                    var id    = $(this).data('id');
                    $.ajax({
                        dataType : "json",
                        type     : 'POST',
                        url      : base_url + 'admin/access/remove',
                        data     : { id: id, title: title, _token: $("meta[name='_token']").attr('content')},
                        success: function (data) {console.log('removed');},
                        error: function (data) {console.log('error');}
                    });
                }
            });
        });

        $(document).on('change', '.roles', function (e){
            var name = $(this).data('name');

            showTagsRole(name);
        });

        function showTagsRole(name){
            if(name == 'Editeur' || name == 'Administrateur'){
                $('#access_tags').show();
            }
            else{
                $('#access_tags').hide();
            }
        }
    }
});