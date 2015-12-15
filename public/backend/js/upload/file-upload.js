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

    $('#uploadModal').on('show.bs.modal', function ()
    {
        console.log('vber');
        var $manager = $('#fileManager');

        $.post( "admin/files", { path: 'files/uploads' }).done(function( data ){
            $manager.empty().append(data);
            $manager.data('path','files/uploads');
        });

    })

    $('body').on('click','.file-upload-chosen' ,function(e)
    {
        e.preventDefault();
        e.stopPropagation();

        var image  = '';
        var exts   = ['jpg','jpeg','png','gif'];
        var target = $(this).data('targetid');
        var value  = $(this).attr('href');

        var get_ext = value.split('.');
        get_ext     = get_ext.reverse();

        // check file type is valid as given in 'exts' array
        if ( $.inArray ( get_ext[0].toLowerCase(), exts ) > -1 )
        {
            image = '<img class="file-choosen file-image thumbnail" src="' + value + '" alt="image">';
        }

        var input  = '<input class="file-choosen" type="hidden" name="' + target + '" value="' + value + '">' + image;
        var html   = '<div class="file-choosen-wrapper">' + input + '<button class="btn btn-xs btn-danger file-remove">x</button></div>';
        var div    = $('#'+target).parent().find('.file-input');

        $('#'+target).parent().find('.file-choosen-wrapper').remove();

        $(div).append(html);

    });

    $('body').on('click','.file-folder' ,function(e){

        e.preventDefault();
        e.stopPropagation();

        var $manager  = $('#fileManager');
        var path      = $(this).attr('href');

        $.post( "admin/files", { path: path }).done(function( data ){
            $manager.empty().append(data);
            $manager.data('path',path);
        });

    });

    $('body').on('click','.file-remove' ,function(e){

        e.preventDefault();
        e.stopPropagation();

        $(this).closest('.file-choosen-wrapper').remove();

    });

    var myDropzone = new Dropzone("div#dropzone", { url: "admin/upload"});

    myDropzone.on('sending', function(file, xhr, formData){
        var path =  $('#fileManager').data('path');
        formData.append('path', path);
    });

    myDropzone.on("success", function(file) {

        var $manager  = $('#fileManager');
        var path      =  $manager.data('path');

        $.post( "admin/files", { path: path }).done(function( data ){
            $manager.empty().append(data);
            $manager.data('path',path);
        });

    });

});
