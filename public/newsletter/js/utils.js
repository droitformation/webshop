$(function() {

    // Match page height with Sidebar Height
    function checkpageheightAgain() {
        var sh = $("#page-leftbar").height();
        var ch = $("#wrap").height();

        if (sh > ch){
            $("#page-content").css("min-height",ch+"px");
        }
        else{
            $("#page-content").css("min-height",sh+"px");
        }
    }

    $('body').on('click','.deleteContentBloc',function(event){

        var $this  = $(this);
        var action = $this.data('action');
        var id     = $this.data('id');
        var _token = $("meta[name='_token']").attr('content');
        var answer = confirm('Voulez-vous vraiment supprimer : '+ action +' ?');

        if (answer)
        {
            $.ajax({
                url     : 'admin/campagne/remove',
                data    : { id: id , _token : _token},
                type    : "POST",
                success : function(data) {
                    if(data == 'ok')
                    {
                        console.log('ok remove');
                        $('#bloc_rang_'+id).remove();
                        checkpageheightAgain();
                    }
                }
            });
        }

        return false;

    });

    /**
     *  Create and edit newsletter blocs
     */
    $('body').on('click','.editContent',function(event){

        var $this  = $(this);
        var id     = $this.data('id');

        $('.create_bloc').hide();
        $('.edit_content_form').hide();
        $this.hide();

        $('#edit_'+id).show();

        // height
        //var h = $('#edit_'+id).height();
        //$('#bloc_rang_'+ id).css("height",h);

        $( "#sortable" ).sortable( "disable" );
        $( "#sortGroupe" ).sortable( "enable" );
        $( "#sortGroupe .groupe_rang").css({ "border":"1px solid #bfe4ad"});

    });

    $('body').on('click','.cancelEdit',function(event){

        $('.edit_content_form').hide();
        $( "#sortable" ).sortable( "enable" );
        $( "#sortGroupe" ).sortable( "disable" );
        $('.bloc_rang').height('auto');
    });

    $('body').on('click','.cancelCreate',function(event){
        $('.create_bloc').hide();
        $( "#sortable" ).sortable( "enable" );
    });

    $('body').on('click','.blocEdit',function(event){
        var $this  = $(this);
        var id     = $this.attr('rel');
        var w = $( document ).width();
        w = w - 890;
        var h = $( document ).height();

        $('.create_bloc').hide();
        $('.edit_content_form').hide();

        var content = $('#create_'+id);
        content.find('.create_content_form').css("width",w);

        setTimeout(function() {
            // height
            var h = $('#create_'+id + ' .create_content_form').height();
            console.log(h);
            $('#create_'+id).css("height",h-100);

        }, 100);

        $('#create_'+id).show();
        $( "#sortable" ).sortable( "disable" );
    });

});
