!function ($) {
    $(function(){

        /*****************************************************************
         *   Delete confirm action
         *****************************************************************/
        $('body').on('click','.doAction',function(event){

            var $this  = $(this);
            var action = $this.data('action');
            var what   = $this.data('what');
            var answer = confirm('Voulez-vous vraiment '+ what +' '+ action +' ?');

            if (answer){
                return true;
            }
            return false;
        });


    });
}(window.jQuery);