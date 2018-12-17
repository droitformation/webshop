$( function() {
    /*
     * Inscription form clones
     */
    $('body').on("click", '#cloneBtn' ,function(e) {

        var $wrapper_clone  = $('#wrapper_clone');
        var $fieldset_clone = $('#fieldset_clone');

        e.preventDefault(); e.stopPropagation();

        var clone = $fieldset_clone.clone();

        var length       = $('.field_clone').length;
        var $options     = clone.find('.option-input');
        var $texts       = clone.find('.text-input');
        var $occurrences = clone.find('.occurrence-input');
        var $radios      = clone.find('.group-input');

        $radios.each(function(){
            var name = $(this).attr('name');
            var name = name.replace('groupes[0]', 'groupes[' + length + ']');
            $(this).attr('name', name);
        });

        $options.each(function(){
            var oname = $(this).attr('name');
            var oname = oname.replace('options[0]', 'options[' + length + ']');
            $(this).attr('name', oname);
        });

        $texts.each(function(){
            var oname = $(this).attr('name');
            var id    = $(this).data('option_id');
            console.log(id);
            var oname = oname.replace('options[0][][' + id + ']', 'options[' + length + '][][' + id + ']');
            $(this).attr('name', oname);
        });

        $occurrences.each(function(){
            var oname = $(this).attr('name');
            var oname = oname.replace('occurrences[0]', 'occurrences[' + length + ']');
            $(this).attr('name', oname);
        });

        clone.attr('id', '');
        clone.prepend('<a href="#" class="remove">x</a>');
        clone.appendTo($wrapper_clone);
    });

    $('body').on("click", '.remove' ,function(e) {
        e.preventDefault(); e.stopPropagation();

        $(this).closest('fieldset').remove();
    });

    $('body').on("blur", '.participant-input' ,function(e) {
        var val = $(this).val();
        if( !val.includes(',') ){
            alert('Vérifier que le nom et prénom sont séparés par une virgule');
        }
    });

});