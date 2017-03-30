'use strict';

var sortable = $('sortable');

dragula([document.getElementById('left-defaults'), document.getElementById('right-defaults') , document.getElementById('middle-defaults')],{
        accepts: function (el, target, source, sibling) {

            var listsNum = target.children.length;

            if( (target.id === "right-defaults") && listsNum > 0) {
                return false;
            }

            return true; // elements can be dropped in any of the `containers` by default
        }
    })
    .on('drop', function (el,target) {
        el.className += ' ex-moved';

        var id     = $(el).data('id');
        var inputs = $('#ids').val();
        var transvase_id = $('#transvase_id');
        var values = inputs.split(',');

        values = values.filter(function(e){return e});
        values = $.unique( values );

        if(target.id === 'left-defaults') {
            values.push(id);

            $('#ids').val(values.join(','));
        }

        if(target.id === 'middle-defaults' || target.id === 'right-defaults') {
            var index = values.indexOf(String(id));
            if(index != -1){
                values.splice( index, 1 );
            }

            $('#ids').val(values.join(','));
        }

        if(target.id === 'right-defaults') {
            transvase_id.val(id);
        }

    });

dragula([sortable]);
