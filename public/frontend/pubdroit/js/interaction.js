!function ($) {
    $(function(){

        function acceptTermsAndConditions(){

            // Terms and conditions have to be checked
            if( !$('input[id=termsAndConditions]').is(':checked'))
            {
                swal({
                    title: "Attention",
                    text : 'Vous devez accepter les conditions générales',
                    type : "warning",
                    showConfirmButton: true
                });

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

            return true;
        });

        /*****************************************************************
         *   Choose payment
         *****************************************************************/
        $('input[class=paymentType]').click(function() {

            var type = $(this).attr('id');

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

   // swal("Good job!", "You clicked the button!", "success");


}(window.jQuery);