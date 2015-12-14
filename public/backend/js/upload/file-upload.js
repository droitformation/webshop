$( function() {


    $('#uploadModal').on('show.bs.modal', function (e) {

        var target = $(e.relatedTarget).attr('id');
        var $body  = $(this).find('.modal-body');

        $.get( "admin/imageJson", function( data ) {

            var list = '<ul class="file-upload-list">';
            $.each(data, function( i, item ) {
               // console.log(item.image);

                list += '<li class="file-upload-item">'
                     + '<a id="' + target + '" href="' + item.image + '" class="file-upload-chosen">'
                     + '<img src="' + item.image + '" alt="" />'
                     + '</a>'
                     + '</li>';
            });

            list += '</ul>';

            $body.append(list);

            console.log(list);
        });

    });

    $('body').on('click','.file-upload-chosen' ,function(e){
        e.preventDefault();
        e.stopPropagation;
        var target = $('this').attr('id');
        $('#'+target).after('<input name="file" value="wde">');
    });

});
