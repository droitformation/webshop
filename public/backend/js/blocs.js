
$( function() {

    var base_url = location.protocol + "//" + location.host+"/";

    var $wrapper = $('#content-bloc-wrapper');

    if($wrapper.length){

        $('body').on("click", '.new-bloc-content' ,function(e) {
            e.preventDefault(); e.stopPropagation();

            var type = $(this).data('type');

            $.get( "admin/content/" + type , function( data ) {
                console.log(data);

                $('#bloc-wrapper').html(data);

            });
        });
    }

});