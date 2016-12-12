$( function() {

        
    $('.redactor').redactor({
        minHeight  : 250,
        maxHeight: 450,
        formattingTags: ['p', 'h2', 'h3','h4'],
        lang: 'fr',
/*        uploadcare: {
            // styling options
            buttonLabel: 'Image / file',
            buttonBefore: 'video',
            buttonIconEnabled: true,
            // uploadcare widget options, see https://uploadcare.com/documentation/widget/#configuration
            publicKey: 'demopublickey', // set your API key
            crop: 'free'
        },*/
        plugins: ['imagemanager','filemanager','source','iconic','alignment','uploadcare'],
        fileUpload : 'admin/uploadRedactor?_token=' + $('meta[name="_token"]').attr('content'),
        imageUpload: 'admin/uploadRedactor?_token=' + $('meta[name="_token"]').attr('content'),
        imageManagerJson: 'admin/imageJson?_token=' + $('meta[name="_token"]').attr('content'),
        fileManagerJson: 'admin/fileJson?_token=' + $('meta[name="_token"]').attr('content'),
        imageResizable: true,
        imagePosition: true,
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