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
                        $row.empty();
                        $row.append(data);
                    },
                    error  : function(){
                        alert('problème');
                    }
                });
            }

        });

    }
});