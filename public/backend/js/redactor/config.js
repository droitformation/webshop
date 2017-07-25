$( function() {

        
    $('.redactor').redactor({
        minHeight  : 250,
        maxHeight: 450,
        removeEmpty : [ 'strong' , 'em' , 'span' , 'p' ],
        lang: 'fr',
        plugins: ['imagemanager','filemanager','fontsize','fontcolor'],
        fileUpload : 'admin/uploadRedactor?_token=' + $('meta[name="_token"]').attr('content'),
        imageUpload: 'admin/uploadRedactor?_token=' + $('meta[name="_token"]').attr('content'),
        imageManagerJson: 'admin/imageJson?_token=' + $('meta[name="_token"]').attr('content'),
        fileManagerJson: 'admin/fileJson?_token=' + $('meta[name="_token"]').attr('content'),
        imageResizable: true,
        imagePosition: true,
        buttons    : ['html','formatting','bold','italic','|','unorderedlist','orderedlist', '|','image','file','link','alignment']
    });

    $('.redactorSimple').redactor({
        minHeight: 50,
        maxHeight: 270,
        lang: 'fr',
        plugins: ['imagemanager','filemanager'],
        fileUpload : 'admin/uploadRedactor?_token=' + $('meta[name="_token"]').attr('content'),
        fileManagerJson: 'admin/fileJson?_token=' + $('meta[name="_token"]').attr('content'),
        imageUpload: 'admin/uploadRedactor?_token=' + $('meta[name="_token"]').attr('content'),
        imageManagerJson: 'admin/imageJson?_token=' + $('meta[name="_token"]').attr('content'),
        plugins: ['iconic'],
        buttons  : ['html','format','bold','italic','link','image','file','|','unorderedlist','orderedlist']
    });

});