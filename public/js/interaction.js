!function ($) {
    $(function(){

        function acceptTermsAndConditions(){

            // Terms and conditions have to be checked
            if( !$('input[id=termsAndConditions]').is(':checked'))
            {
                alert('Vous devez accepter les conditions générales');
                return false;
            }

            return true;
        };

        /*****************************************************************
         *   Delete confirm action
         *****************************************************************/
        $('body').on('click','.doAction',function(event){

            var $this  = $(this);
            var checked = $this.data('checked');

            if(checked && !acceptTermsAndConditions()){
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

        /*****************************************************************
         *   Choose payment
         *****************************************************************/
        $('input[class=paymentType]').click(function() {

            var type = $(this).attr('id');

            console.log('payment-'+type);

            $('.btn-commande').hide();
            $('#btn-'+type).show();

        });

        $('#payment-stripe').on('show.bs.modal', function (event)
        {
            if(!acceptTermsAndConditions())
            {
                $('#payment-stripe').modal('hide');

                return false;
            }

        })

    });


}(window.jQuery);