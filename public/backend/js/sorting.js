var url  = location.protocol + "//" + location.host+"/";

$(function() {

    $( ".sortable" ).sortable({
        axis: 'y',
        update: function (event, ui) {
            var data = $(this).sortable('serialize') +"&_token=" + $("meta[name='_token']").attr('content');
            // POST to server using $.post or $.ajax
            $.ajax({
                data: data,
                type: 'POST',
                url: url+ 'admin/page/sorting'
            });
        }
    });

    $( ".sortquestion" ).sortable({
        axis: 'y',
        update: function (event, ui) {
            var id   =  $(this).data('id');
            var data = $(this).sortable('serialize') +"&id="+ id +"&_token=" + $("meta[name='_token']").attr('content');
            // POST to server using $.post or $.ajax
            $.ajax({
                data: data,
                type: 'POST',
                url: url+ 'admin/sondage/sorting'
            });
        }
    });

    $( ".sortcontent" ).sortable({
        axis: 'y',
        update: function (event, ui) {
            var data = $(this).sortable('serialize') +"&_token=" + $("meta[name='_token']").attr('content');
            // POST to server using $.post or $.ajax
            $.ajax({
                data: data,
                type: 'POST',
                url: url+ 'admin/pagecontent/sorting'
            });
        }
    });

    $( ".sortslides" ).sortable({
        axis: 'y',
        update: function (event, ui) {
            var id   = $(this).data('id');
            var data = $(this).sortable('serialize') +"&id="+ id +"&_token=" + $("meta[name='_token']").attr('content');
            // POST to server using $.post or $.ajax
            $.ajax({
                data: data,
                type: 'POST',
                url: url+ 'admin/slide/sorting'
            });
        }
    });

});