$( function() {

    /********
     *  First load uploads folder in manager
     * *********/

    $('body').on('show.bs.modal','#uploadModal', function ()
    {
        var $manager  = $('#fileManager');
        var $tree     = $('#fileManagerTree');
        var $gallery  = $('#gallery');

        $gallery.html('<li style="width: 100%; height: 300px;line-height: 300px;text-align: center;"><img style="width: 60px; height: 60px;" src="' + base_url + '/images/default.svg" /></li>');

        $.post( "admin/files", { path: 'files/uploads' }).done(function( data ){
            $manager.empty().append(data);
            $manager.data('path','files/uploads');

            $('#gallery').isotope({
                itemSelector: '.file-item',
                masonry     : {layoutMode: 'fitColumns', columnWidth: 120}
            });
        });

        $.get( "admin/tree", function( data ) {
            $tree.empty().append(data);
        });

        var myDropzone = new Dropzone("div#dropzone", {
            url: "admin/upload",
            dictDefaultMessage: "Ajouter une image",
            dictRemoveFile: "Enlever",
            thumbnailWidth: 100,
            thumbnailHeight: 80,
            addRemoveLinks : true
        });

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

                $('#gallery').isotope({
                    itemSelector: '.file-item',
                    masonry: {
                        layoutMode: 'fitColumns', columnWidth: 120
                    }
                });
            });

        });
    });

    $('body').on('shown.bs.modal','#uploadModal', function ()
    {
        var $content = $(this).find('.modal-content');
        var $body    = $(this).find('.modal-body');
        var maxHeight = $content.height() - 100;
        $body.css({ 'height' : maxHeight });

    });

    /********
     *  Choose file in manager
     * *********/

    $('body').on('click','.file-upload-chosen' ,function(e)
    {
        e.preventDefault();
        e.stopPropagation();

        var cropper = new Croppic('cropManager');
        cropper.destroy();

        var exts   = ['jpg','jpeg','png','gif'];
        var target = $(this).data('targetid');
        var value  = $(this).attr('href');

        var get_ext = value.split('.');
        get_ext     = get_ext.reverse();

        // check file type is valid as given in 'exts' array
        if ( $.inArray ( get_ext[0].toLowerCase(), exts ) > -1 )
        {
            var image = '<img class="file-choosen file-image thumbnail" src="' + value + '" alt="image">';
        }
        else
        {
            var filename = value.replace(/^.*[\\\/]/, '')
            var image    = '<a target="_blank" class="file-choosen" href="' + value + '">' + filename + '</a>';
        }

        var input  = '<input class="file-choosen" type="hidden" name="' + target + '" value="' + value + '">' + image;
        var html   = '<div class="file-choosen-wrapper">' + input + '<button class="btn btn-xs btn-danger file-remove">x</button></div>';
        var div    = $('#'+target).parent().find('.file-input');

        $('#'+target).parent().find('.file-choosen-wrapper').remove();

        $(div).append(html);

    });

    /********
     * Deleter file in manager
     * *********/

    $('body').on('click','.file-manager-delete' ,function(e){

        e.preventDefault();
        e.stopPropagation();

        var $this  = $(this);
        var action = $this.data('action');
        var what   = $this.data('what');
        var answer = confirm('Voulez-vous vraiment ' + what + ' : '+ action +' ?');

        if (answer)
        {
            var src    = $(this).data('src');
            var button = $(this);

            $.post( "admin/files/delete", { src: src }).done(function( data )
            {
                if(data)
                {
                    var $image = button.closest('.file-item');
                    $('#gallery').isotope('remove', $image).isotope('layout');
                }
            });
        }

        return false;

    });

    /********
     *  Change folder in manager
     * *********/
    $('body').on('click','.file-folder' ,function(e){

        e.preventDefault();
        e.stopPropagation();

        var cropper = new Croppic('cropManager');
        cropper.destroy();

        var $manager  = $('#fileManager');
        var $gallery  = $('#gallery');

        var path      = $(this).attr('href');

        $gallery.html('<li style="width: 100%; height: 300px;line-height: 300px;text-align: center;"><img style="width: 60px; height: 60px;" src="' + base_url + '/images/default.svg" /></li>');

        $.post( "admin/files", { path: path }).done(function( data )
        {
            $manager.empty().append(data);
            $manager.data('path',path);

            $('#gallery').isotope({
                itemSelector: '.file-item',
                masonry: {layoutMode: 'fitColumns', columnWidth: 120}
            });
        });

        $('.file-folder').removeClass('active');
        $(this).addClass('active');

    });

    /********
     * Remove choosen file
     * *********/

     $('body').on('click','.file-remove' ,function(e){

        e.preventDefault();
        e.stopPropagation();

        $(this).closest('.file-choosen-wrapper').remove();
    });

    /********
     * Cropp image in manager
     * *********/

    $('body').on('click','.file-manager-crop' ,function(e){

        e.preventDefault();
        e.stopPropagation();

        var src = $(this).data('src');

        var cropperOptions = {
            cropUrl    : 'admin/files/crop',
            loadPicture: src,
            modal      : true,
            onAfterImgCrop: function()
            {
                console.log('onAfterImgCrop');
                var cropper = new Croppic('cropManager');
                cropper.destroy();
            },
            cropData:{
                "_token": $('meta[name="_token"]').attr('content')
            }
        }

        var cropperHeader = new Croppic('cropManager', cropperOptions);

    });


});
