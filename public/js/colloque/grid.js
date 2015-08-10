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
        $( this ).parent('.grid-item').toggleClass('is-expanded');

        var id   = $( this ).data('colloque');
        var body = $('#colloque_' + id + ' .body');

        $.get(url + "colloque/" + id, {}, function( data )
        {

            body.append(data);

            console.log(data);
        });

        $grid.isotope('layout');
    });

});

var url = location.protocol + "//" + location.host+"/";

new Vue({

    el: '#colloques',

    data: {
        colloques: []
    },

    ready: function () {

        this.fetchData();
    },

    methods: {
        fetchData: function ()
        {
            $.ajax({
                context: this,
                url: "colloque",
                success: function (result) {
                    this.$set("colloques", result)
                }
            });
        },

        open:function (e) {

            console.log(e.id);
            $.ajax({
                context: this,
                url: "colloque/" + e.id,
                success: function (result) {

                    var body = $('#colloque_' + e.id + ' .body');
                }
            });

        }

    }
});
