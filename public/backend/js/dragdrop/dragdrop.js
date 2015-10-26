'use strict';

var sortable = $('sortable');

dragula([document.getElementById('left-defaults'), document.getElementById('right-defaults') , document.getElementById('middle-defaults')])
    .on('drop', function (el) {
        el.className += ' ex-moved';
    });

dragula([sortable]);
