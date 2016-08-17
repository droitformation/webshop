$(document).ready( function() {

    var url = location.protocol + "//" + location.host+"/";

    var $grid = $('.grid').isotope({
        itemSelector: '.grid-item',
        masonry: {
            columnWidth: 360
        }
    });

    var $closing = $('.closing');

    $closing.on( 'click', function(e) {
        $('.grid-item').removeClass('is-expanded');
        $('.grid-item .inner').empty();
        $grid.isotope('layout');

        e.preventDefault();
        e.stopPropagation();
        return false;
    });

    $grid.on( 'click', '.grid-item-content', function() {

        var self      = $( this );
        var $gridItem = self.parent('.grid-item');

        if(!$gridItem.hasClass('is-expanded'))
        {
            $('.grid-item').removeClass('is-expanded');
            $('.grid-item .inner').empty();
            $grid.isotope('layout');

            var id     = self.data('colloque');
            var $body  = $('#colloque_' + id + ' .body .inner');

            $.get(url + "colloque/" + id, {}, function( data )
            {
                $body.html(data);
                $gridItem.toggleClass('is-expanded');
                $grid.isotope('layout');
            });
        }
    });

});

var url = location.protocol + "//" + location.host+"/";
