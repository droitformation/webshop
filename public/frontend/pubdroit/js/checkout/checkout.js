!function ($) {
    $(function(){

        /*****************************************************************
        *   Quantitiy buttons on products in cart
         *****************************************************************/

            $(".ddd").on("click", function (event) {

                var $button = $(this);
                var $input = $button.closest('.sp-quantity').find("input.quntity-input");

                $input.val(function(i, value) {
                    return +value + (1 * +$button.data('multi'));
                });
            });

            // declare a variables first
            var $hoverMeElement = $('#checkout figure');

            // use this variable
            $hoverMeElement.hover(
                function() {
                    $(this).find('button').show();
                }, function() {
                    $(this).find('button').hide();
                }
            );

        /****************************************************************
         *
         ****************************************************************/

        // unserialize.js v1.0.1
        function unserialize(serialize) {
            let obj = {};
            serialize = serialize.split('&');
            for (let i = 0; i < serialize.length; i++) {
                thisItem = serialize[i].split('=');
                obj[decodeURIComponent(thisItem[0])] = decodeURIComponent(thisItem[1]);
            };
            return obj;
        };

        /*****************************************************************
         *   Update user infos during checkout
         *****************************************************************/

       /* $('#updateAdresse').parsley();

        $('#updateAdresse').on('submit', function(e) {
            e.preventDefault(); //prevent form from submitting
            e.stopPropagation(); //prevent form from submitting

            var data = $( this ).serializeArray();
            var id   = $('#updateSubmit').data('id');

            $.ajax({
                url     : 'ajax/adresse/' + id,
                data    : { data : data},
                type    : "POST",
                success : function(result) {
                    if(result)
                    {
                       $('#userFormModal').modal('hide');
                       $('#userAdresse').empty();
                       $('#userAdresse').html(result);
                    }
                }
            });

        });*/

        /****************************************************************/

    });
}(window.jQuery);