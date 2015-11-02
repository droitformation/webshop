$( function() {

    var base_url = location.protocol + "//" + location.host+"/";

    $.get(base_url + 'admin/specialisation', function( data )
    {
        console.log(data);
        var tags = data;
    });

    $("#tags").tagit({
        fieldName          : "specialisation",
        placeholderText    : "Rechercher une spécialisation",
        removeConfirmation : true,
        availableTags      : tags,
        afterTagAdded: function(event, ui) {
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
                    success: function( data ) {
                        console.log('added');
                    },
                    error: function(data) {  console.log('error');  }
                });
            }
        },
        beforeTagRemoved: function(event, ui) {

            var specialisation = ui.tagLabel;
            var id    = $(this).data('id');
            var model = $(this).data('model');

            var answer = confirm('Voulez-vous vraiment supprimer : '+ specialisation +' ?');
            if (answer) {
                $.ajax({
                    dataType : "json",
                    type     : 'POST',
                    url      : base_url + 'admin/specialisation/destroy',
                    data     : {_method: 'delete', id: id, model : model, specialisation: specialisation, _token: $("meta[name='_token']").attr('content')},
                    success: function (data) {
                        console.log('removed');
                    },
                    error: function (data) {console.log('error');}
                });
            }
            else { return false; }
        }
    });

});