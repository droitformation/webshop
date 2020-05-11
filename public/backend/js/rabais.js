$(function() {

    let url  = location.protocol + "//" + location.host+"/";

    if($('.select-rabais').length){

        let select2 = $('.select-rabais').select2({
            ajax: {
                url: base_url + 'admin/rabais/all',
                dataType: 'json',
                data: function (params) {
                    let query = {
                        title: params.term,
                        user: $('#rabaisSelect').data('id')
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                }
            }
        });

        let rabais = $('#rabaisSelect').data('rabais');
    console.log(rabais);
        select2.val(rabais);
        select2.trigger('change');

        select2.on('select2:select', function (e) {

            //console.log(e.params.data.id);
            let id = e.params.data.id;
            let user = $('#rabaisSelect').data('id');
            let result = false;

            $.ajax({
                type   : "POST",
                async: "false",
                url    : base_url + "admin/rabais/add",
                data   : { id : id, user : user,  _token: $("meta[name='_token']").attr('content') },
                success: function(data) {
                    console.log(data.result);
                    result = data.result;
                },
                error  : function(){ alert('problème'); }
            });

            if(result){
                e.preventDefault();
            }
            else{
                alert('yes');
            }

        });

        select2.on('select2:unselect', function (e) {
            let id = e.params.data.id;
            let user = $('#rabaisSelect').data('id');

            $.ajax({
                type   : "POST",
                url    : base_url + "admin/rabais/remove",
                data   : { id : id, user : user,  _token: $("meta[name='_token']").attr('content') },
                success: function(data) {
                    console.log(data.result);
                    alert('yes');
                },
                error  : function(){ alert('problème'); }
            });
        });

    }
});

