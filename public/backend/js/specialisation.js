$( function() {

    var base_url = location.protocol + "//" + location.host+"/";

    $("#tags").tagit({
        fieldName          : "specialisation",
        placeholderText    : "Rechercher une spécialisation",
        removeConfirmation : true,
        afterTagAdded: function(event, ui) {
            if(!ui.duringInitialization)
            {
                var specialisation = ui.tagLabel;
                var colloque_id    = $(this).data('id');

                $.ajax({
                    dataType : "json",
                    type     : 'POST',
                    url      : base_url + 'admin/specialisation',
                    data: {  colloque_id  : colloque_id, specialisation : specialisation , _token: $("meta[name='_token']").attr('content') },
                    success: function( data ) {
                        console.log('added');
                    },
                    error: function(data) {  console.log('error');  }
                });
            }
        },
        beforeTagRemoved: function(event, ui) {

            var specialisation = ui.tagLabel;
            var colloque_id    = $(this).data('id');

            var answer = confirm('Voulez-vous vraiment supprimer : '+ specialisation +' ?');
            if (answer) {
                $.ajax({
                    dataType : "json",
                    type     : 'POST',
                    url      : base_url + 'admin/specialisation/destroy',
                    data     : {_method: 'delete', colloque_id: colloque_id, specialisation: specialisation, _token: $("meta[name='_token']").attr('content')},
                    success: function (data) {
                        console.log('removed');
                    },
                    error: function (data) {console.log('error');}
                });
            }
            else
            {
                return false;
            }
        },
        autocomplete: {
            delay: 0,
            minLength: 2,
            source: function( request, response ) {
                $.ajax({
                    dataType : "json",
                    type     : 'GET',
                    url      : base_url + 'admin/specialisation/search',
                    data: {  term: request.term , _token: $("meta[name='_token']").attr('content') },
                    success: function( data ) {
                        response( $.map( data, function( item ) {
                            return {
                                label: item.label,
                                value: item.label
                            }
                        }));
                    },
                    error: function(data) {  console.log('error');  }
                });
            }
        }
    });

});