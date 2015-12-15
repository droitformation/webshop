$( function() {


/*    $('#uploadModal').on('show.bs.modal', function (e) {

        var target = $(e.relatedTarget).attr('id');
        var $body  = $(this).find('.modal-body');

        $.get( "admin/imageJson", function( data ) {

            var list = '<ul class="file-upload-list">';
            $.each(data, function( i, item ) {

                list += '<li class="file-upload-item">'
                     + '<a data-targetid="' + target + '" href="' + item.title + '" class="file-upload-chosen">'
                     + '<img src="' + item.image + '" alt="" />'
                     + '</a>'
                     + '</li>';
            });

            list += '</ul>';

            $body.append(list);


        });

    });*/

    $('body').on('click','.file-upload-chosen' ,function(e){

        e.preventDefault(); e.stopPropagation();

        $('#uploadModal').hide();

        var target = $(this).data('targetid');
        var value  = $(this).attr('href');

        console.log(target);

        $('#'+target).after('<input type="hidden" name="' + target + '" value="' + value + '">');

    });

});
