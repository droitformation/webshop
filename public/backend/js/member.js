$( function() {

    var base_url = location.protocol + "//" + location.host+"/";

    if($("#members").length)
    {
        $.get(base_url + 'admin/member', function( data )
        {
            $("#members").tagit({
                fieldName          : "member",
                placeholderText    : "Ajouter un membre",
                removeConfirmation : true,
                tagSource      : data,
                afterTagAdded: function(event, ui)
                {
                    if(!ui.duringInitialization)
                    {
                        var member = ui.tagLabel;
                        var id    = $(this).data('id');

                        $.ajax({
                            dataType : "json",
                            type     : 'POST',
                            url      : base_url + 'admin/member',
                            data: { id : id, member : member , _token: $("meta[name='_token']").attr('content') },
                            success: function( data ) {
                                console.log('added');
                            },
                            error: function(data) {  console.log('error');  }
                        });
                    }
                },
                beforeTagRemoved: function(event, ui)
                {
                    var member = ui.tagLabel;
                    var id    = $(this).data('id');

                    var answer = confirm('Voulez-vous vraiment supprimer : '+ member +' ?');
                    if (answer) {
                        $.ajax({
                            dataType : "json",
                            type     : 'POST',
                            url      : base_url + 'admin/member/destroy',
                            data     : {_method: 'delete', id: id, member: member, _token: $("meta[name='_token']").attr('content')},
                            success: function (data) {
                                console.log('removed');
                            },
                            error: function (data) {console.log('error');}
                        });
                    }
                    else { return false; }
                },
                beforeTagAdded: function(event, ui)
                {
                    if ($.inArray(ui.tagLabel, data) == -1)
                    {
                        return false;
                    }
                }
            });
        });

    }

});