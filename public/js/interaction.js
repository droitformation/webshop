!function ($) {
    $(function(){

        /*****************************************************************
         *   Delete confirm action
         *****************************************************************/
        $('body').on('click','.doAction',function(event){

            var $this  = $(this);
            var checked = $this.data('checked');

            if(checked && !$('input[id=termsAndConditions]').is(':checked')){
                alert('Vous devez accepter les conditions générales');
                return false;
            }

            var action  = $this.data('action');
            var what    = $this.data('what');
            var answer  = confirm('Voulez-vous vraiment '+ what +' '+ action +' ?');

            if (answer)
            {
                return true;
            }

            return false;

        });


    });
}(window.jQuery);