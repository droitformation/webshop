$( function() {

    $('.redactor').redactor({
        minHeight  : 250,
        maxHeight: 450,
        formattingTags: ['p', 'h2', 'h3','h4'],
        lang: 'fr',
        plugins: ['imagemanager','filemanager','source','iconic','alignment'],
        fileUpload : 'admin/uploadRedactor?_token=' + $('meta[name="_token"]').attr('content'),
        imageUpload: 'admin/uploadRedactor?_token=' + $('meta[name="_token"]').attr('content'),
        imageManagerJson: 'admin/imageJson?_token=' + $('meta[name="_token"]').attr('content'),
        fileManagerJson: 'admin/fileJson?_token=' + $('meta[name="_token"]').attr('content'),
        buttons    : ['format','bold','italic','|','lists','|','image','file','link','alignment']
    });

    $('.redactorSimple').redactor({
        minHeight: 50,
        maxHeight: 270,
        lang: 'fr',
        plugins: ['iconic'],
        buttons  : ['format','bold','italic','link','|','lists']
    });

});