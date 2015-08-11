$(document).ready( function() {

    var url = location.protocol + "//" + location.host+"/";

    var $grid = $('.grid').isotope({
        itemSelector: '.grid-item',
        masonry: {
            columnWidth: 360
        }
    });

    $grid.on( 'click', '.grid-item-content', function() {
        $('.grid-item').removeClass('is-expanded');
        $('.grid-item .inner').empty();
        $grid.isotope('layout');

        var self   = $( this );
        var id     = self.data('colloque');
        var $body  = $('#colloque_' + id + ' .body .inner');

        $.get(url + "colloque/" + id, {}, function( data )
        {
            $body.html(data);
            self.parent('.grid-item').toggleClass('is-expanded');
            $grid.isotope('layout');
        });
    });

});

var url = location.protocol + "//" + location.host+"/";
