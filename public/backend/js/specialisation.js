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