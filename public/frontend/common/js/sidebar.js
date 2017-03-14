$(function() {

    $('#toggleNewsletter').click(function() {

        if ($('.toggleNewsletter').is(":hidden")) {
            $(this).addClass('toggleActive');
        }
        else {
            $(this).removeClass('toggleActive');
        }

        $('.toggleNewsletter').slideToggle('fast');

        return false;
    });

    // Sidebar arret select
    $("#arret-chosen").chosen();

    $.fn.myFixture = function (settings) {
        return this.each(function () {

            // default css declaration
            var width = $('#appComponent').width();
            
            if(width > 860){
                var elem = $(this).css('position', 'fixed');

                var setPosition = function () {
                    var top = 0;
                    // get no of pixels hidden above the the window
                    var scrollTop = $(window).scrollTop();
                    // get elements distance from top of window
                    var topBuffer = ((settings.topBoundary || 0) - scrollTop);
                    // update position if required
                    if (topBuffer >= 0) { top += topBuffer }
                    elem.css('top', top);
                };

                $(window).bind('scroll', setPosition);
                setPosition();
            }

        });
    };

    $('.fixed').myFixture({ topBoundary: 145 });

});