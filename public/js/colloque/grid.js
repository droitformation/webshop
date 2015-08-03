$(document).ready( function() {

    var $grid = $('.grid').isotope({
        itemSelector: '.grid-item',
        masonry: {
            columnWidth: 360
        }
    });

    $grid.on( 'click', '.grid-item-content', function() {
        $('.grid-item').removeClass('is-expanded');
        $( this ).parent('.grid-item').toggleClass('is-expanded');
        $grid.isotope('layout');
    });

});
