!function ($) {
    $(function(){

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

    });
}(window.jQuery);