$( function() {

    let base_url = location.protocol + "//" + location.host+"/";
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

        clone.find('.options-liste-multiple').remove();

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
        clone.find('.price_type').attr('data-index', length);
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

    $('body').on("change", '.price_type' ,function(e) {

        let form = $(this).data('form');
        let $wrapper = form == 'multiple' ? $(this).closest('.field_clone') : $(this).closest('#main_fieldset');
        let $content = $wrapper.find('.options-liste-box');

        let colloque = $(this).data('colloque');
        let type     = $(this).find(':selected').data('type');
        let price    = $(this).find(':selected').val();
        let index    = $(this).data('index');

        if(type == 'price_link_id'){
            $.ajax({
                type : "POST",
                url  : base_url + 'vue/colloqueoptions',
                data : { colloque : colloque, index : index, form: form,price : price , _token: $("meta[name='_token']").attr('content') },
                success: function(html) {
                    $content.append(html);
                },
                error: function(){alert('problème');}
            });
        }
        else{
            $content.empty();
        }
    });

});