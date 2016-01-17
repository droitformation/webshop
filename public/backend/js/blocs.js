
$( function() {

    var base_url = location.protocol + "//" + location.host+"/";

    var $wrapper = $('#content-bloc-wrapper');

    if($wrapper.length)
    {

        $('body').on("click", '.new-bloc-content' ,function(e) {
            e.preventDefault(); e.stopPropagation();

            var type    = $(this).data('type');
            var page_id = $wrapper.data('page');

            $.get( "admin/content/" + type + "/" + page_id, function( data ) {

                var $bloc = '<div class="bloc-content"><a href="#" class="btn btn-danger btn-xs pull-right remove-bloc-btn">x</a>' + data + '</div>';
                $('#bloc-wrapper').html($bloc);

                $('.redactorBlocSimple').redactor({
                    minHeight: 200,
                    maxHeight: 300,
                    focus    : true,
                    lang     : 'fr',
                    plugins  : ['advanced','imagemanager','filemanager'],
                    fileUpload       : 'uploadFileRedactor?_token=' + $('meta[name="_token"]').attr('content'),
                    imageUpload      : 'uploadRedactor?_token=' + $('meta[name="_token"]').attr('content'),
                    imageManagerJson : 'imageJson',
                    fileManagerJson  : 'fileJson',
                    buttons          : ['html','|','formatting','bold','italic','|','unorderedlist','orderedlist','outdent','indent','|','image','file','link','alignment']
                });

            });
        });

        $('body').on('click','.add-bloc-btn',function(e)
        {
            e.preventDefault(); e.stopPropagation();

            var self   = $(this);
            var $form  = $(this).closest('form');
            var inputs = $form.find('input,textarea,file');

            var map = {};

            inputs.each(function() {
                map[$(this).attr("name")] = $(this).val();
            });

            console.log(map);

            $.ajax({
                type : "POST",
                url  : base_url + "admin/content",
                data : { data: map , _token: $("meta[name='_token']").attr('content') },
                success: function(data)
                {
                    self.closest('.bloc-content').remove();
                    $('#listBlocs').empty().append(data);
                },
                error: function(){alert('problème avec l\'ajout du bloc');}
            });

        });

        $('body').on('click','.edit-bloc-btn',function(e)
        {
            e.preventDefault(); e.stopPropagation();

            var self   = $(this);
            var $form  = $(this).closest('form');
            var inputs = $form.find('input,textarea,file');

            var id  = $form.data('id');
            var map = {};

            inputs.each(function() {
                map[$(this).attr("name")] = $(this).val();
            });

            console.log(map);

            $.ajax({
                type : "POST",
                url  : base_url + "admin/content/" + id,
                data : { data: map , _method: 'put',  _token: $("meta[name='_token']").attr('content') },
                success: function(data)
                {
                    self.closest('.bloc-content').remove();
                    $('#listBlocs').empty().append(data);
                },
                error: function(){alert('problème avec l\'édition du bloc');}
            });

        });

        $('body').on('click','.edit-bloc',function(e)
        {
            e.preventDefault(); e.stopPropagation();

            var id = $(this).data('id');

            $.get( "admin/content/" + id, function( data ) {

                var $bloc = '<div class="bloc-content"><a href="#" class="btn btn-danger btn-xs pull-right remove-bloc-btn">x</a>' + data + '</div>';
                $('#bloc-wrapper').html($bloc);

                $('.redactorBlocSimple').redactor({
                    minHeight: 200,
                    maxHeight: 300,
                    focus    : true,
                    lang     : 'fr',
                    plugins  : ['advanced','imagemanager','filemanager'],
                    fileUpload       : 'uploadFileRedactor?_token=' + $('meta[name="_token"]').attr('content'),
                    imageUpload      : 'uploadRedactor?_token=' + $('meta[name="_token"]').attr('content'),
                    imageManagerJson : 'imageJson',
                    fileManagerJson  : 'fileJson',
                    buttons          : ['html','|','formatting','bold','italic','|','unorderedlist','orderedlist','outdent','indent','|','image','file','link','alignment']
                });

            });
        });

        $('body').on('click','.remove-bloc-btn',function(e)
        {
            e.preventDefault(); e.stopPropagation();
            $(this).closest('.bloc-content').remove();
        });

        $('body').on('click','.delete-bloc',function(e)
        {
            e.preventDefault(); e.stopPropagation();

            var id      = $(this).data('id');
            var page_id = $(this).data('page');

            var answer = confirm('Voulez-vous vraiment supprimer : le bloc ?');

            if (answer)
            {
                $.ajax({
                    type : "POST",
                    url  : base_url + "admin/content/" + id,
                    data : { page_id: page_id, id : id , _method: 'delete',  _token: $("meta[name='_token']").attr('content') },
                    success: function(data)
                    {
                        $('#listBlocs').empty().append(data);
                    },
                    error: function(){alert('problème avec la suppression du bloc');}
                });
            }

            return false;

        });

    }

});