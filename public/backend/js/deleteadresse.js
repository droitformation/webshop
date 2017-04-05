/**
 * Created by cindyleschaud on 03.04.17.
 */
$(function() {

    var url  = location.protocol + "//" + location.host+"/";

    var $table = $('#searchTable');

    if($table.length)
    {
        $('body').on("click", '.deleteAdresseBtn' ,function(e) {

            var id      = $(this).data('id');
            var user_id = $(this).data('user_id');

            var answer = confirm('Voulez-vous vraiment supprimer cette adresse ? Les commandes éventuelles et/ou abonnement seront attachées au compte.');

            if (answer){
                $.ajax({
                    type   : "POST",
                    url    : base_url + "admin/deletedadresses/removeAdresse",
                    data   : { id: id, user_id: user_id, _token: $("meta[name='_token']").attr('content') },
                    success: function(data) {

                        // find row
                        var $row = $('#user_' + user_id);
                        $row.replaceWith(data);
                    },
                    error  : function(){
                        alert('problème');
                    }
                });
            }

        });

        $('body').on("click", '.deleteAdresseRowBtn' ,function(e) {

            var id     = $(this).data('id');
            var answer = confirm('Voulez-vous vraiment supprimer cette adresse ?');

            if (answer){
                $.ajax({
                    type   : "POST",
                    url    : base_url + "admin/deletedadresses/removeAdresseRow",
                    data   : { id: id, _token: $("meta[name='_token']").attr('content') },
                    success: function(data) {
                        var $row = $('#adresse_' + id);
                        $row.replaceWith(data);
                    },
                    error  : function(){
                        alert('problème');
                    }
                });
            }
        });

        $('body').on("click", '.restoreAdresseBtn' ,function(e) {

            var id = $(this).data('id');
            var user_id = $(this).data('user_id');
            var type = $(this).data('type');

            var answer = confirm('Voulez-vous vraiment restaurer cette adresse?');

            if (answer){
                $.ajax({
                    type   : "POST",
                    url    : base_url + "admin/deletedadresses/restoreAdresse",
                    data   : { id: id, user_id: user_id, type:type, _token: $("meta[name='_token']").attr('content') },
                    success: function(data) {
                        // find row
                        var $row = user_id ? $('#user_' + user_id) : $('#adresse_' + id);
                        $row.replaceWith(data);
                    },
                    error  : function(){
                        alert('problème');
                    }
                });
            }
        });
        
    }
});