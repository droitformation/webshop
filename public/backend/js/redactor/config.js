$( function() {

    $('.redactor').redactor({
        minHeight  : '350px',
        maxHeight: '450px',
        removeEmpty : [ 'strong' , 'em' , 'span' , 'p' ],
        lang: 'fr',
        plugins: ['imagemanager','filemanager','fontsize','fontcolor','alignment','linkbtn'],
        fileUpload : 'admin/uploadRedactor?_token=' + $('meta[name="_token"]').attr('content'),
        imageUpload: 'admin/uploadRedactor?_token=' + $('meta[name="_token"]').attr('content'),
        imageManagerJson: 'admin/imageJson?_token=' + $('meta[name="_token"]').attr('content'),
        fileManagerJson: 'admin/fileJson?_token=' + $('meta[name="_token"]').attr('content'),
        imageResizable: true,
        imagePosition: true,
        linkNewTab: true,
        formatting: ['h1', 'h2','h3','p', 'blockquote']
    });

    $('.redactorSimple').redactor({
        minHeight: '180px',
        maxHeight: '370px',
        removeEmpty : [ 'strong' , 'em' , 'span' , 'p' ],
        lang: 'fr',
        plugins: ['imagemanager','filemanager','fontsize','fontcolor','alignment','linkbtn'],
        fileUpload : 'admin/uploadRedactor?_token=' + $('meta[name="_token"]').attr('content'),
        imageUpload: 'admin/uploadRedactor?_token=' + $('meta[name="_token"]').attr('content'),
        imageManagerJson: 'admin/imageJson?_token=' + $('meta[name="_token"]').attr('content'),
        fileManagerJson: 'admin/fileJson?_token=' + $('meta[name="_token"]').attr('content'),
        imageResizable: true,
        imagePosition: true,
        linkNewTab: true,
        formatting: ['h1', 'h2','h3','p', 'blockquote']
    });


    $('.redactorLimit').redactor({
        minHeight: '180px',
        maxHeight: '280px',
        removeEmpty : ['strong' , 'em' , 'span' , 'p' ],
        lang: 'fr',
        formatting: ['p'],
        linkNewTab: true,
        buttons: ['format','|','lists']
    });
});