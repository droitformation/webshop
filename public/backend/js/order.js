/*
 * Select row
 * Product add to new order
 * */

var base_url = location.protocol + "//" + location.host+"/";

// Initialize select
$('.chosen-select').chosen();

$('body').on("click", '#cloneBtnOrder' ,function(e) {

    var $wrapper_clone  = $('#wrapper_clone_order');
    var $fieldset_clone = $('#fieldset_clone_order');

    e.preventDefault();
    e.stopPropagation();

    var length = $('.field_clone_order').length;
    var clone  = $fieldset_clone.clone();

    clone.find('input[type="text"]').val('');

    // Checkbox
    var $checkbox = clone.find('input[type="checkbox"]');
    var name      = $checkbox.attr('name');
    name          = name.replace('order[gratuit][]', 'order[gratuit][' + length + ']');

    $checkbox.attr('name', name);
    $checkbox.attr('checked', false);

    var select = clone.find('.chosen-select');

    $(select).removeClass("chzn-done").removeAttr("id").css("display", "block").next().remove();

    clone.attr('id', '');
    clone.appendTo($wrapper_clone);

    select.chosen();
});

$('body').on("click", '.remove_order' ,function(e) {
    e.preventDefault(); e.stopPropagation();
    $(this).closest('fieldset').remove();
});


/***************************************************
 * Generate invoice if doesn't exist
 ***************************************************/

$('body').on("click", '.order-generate' ,function(e) {

     var id       = $(this).data('id');
     var $button  = $(this);
     var $wrapper = $('#wrapper_' + id);
     var rand     = Math.floor((Math.random() * 1000) + 1);

     $button.hide();
     $wrapper.html('<img style="width: 20px; height: 18px;" src="' + base_url + '/images/default.svg" />');

     $.ajax({
         type   : "POST",
         url    : base_url + "admin/order/generate",
         data   : { id: id, _token: $("meta[name='_token']").attr('content') },
         success: function(data) {
             $wrapper.empty();
             $wrapper.append('<a target="_blank" href="' + data.facture + '?' + rand + '" class="btn btn-xs btn-default">Facture en pdf</a>');
             //'La facture a été regénéré'
             $button.remove();
         },
         error  : function(){
             $button.show();
             alert('problème avec le pdf');
         }
     });
});

$( function() {
    console.log('wqdwqf');

    let $input_wrapper = $('.field_clone_order');

    console.log($input_wrapper);

    $input_wrapper.each( function( index, element ){

        let $product   = $(this).find('.input-product');
        let $qty       = $(this).find('.input-qty');
        let $error_qty = $(this).find('.error_qty');

        $error_qty.hide();

        let $product_id = $product.val();
        let $qty_id = $qty.val();

        $product.change(function() {
            let $product_id = $product.val();
            console.log('change '+$product_id);
            check($product_id, $qty_id, $qty, $error_qty)
        });

        console.log($product_id);
        console.log($qty_id);

        $qty.change(function() {
            console.log('change '+$qty_id);
            check($product_id, $qty_id, $qty, $error_qty)
        });

    });

    function check($product_id, $qty_id, $el, $error){

        $.ajax({
            type   : "POST",
            url    : base_url + "admin/stock/qty",
            data   : { id: $product_id, qty: $qty_id, _token: $("meta[name='_token']").attr('content') },
            success: function(data) {
                if(!data.result){
                    $el.css('border-color','red');
                    $error.show();
                }
                else{
                    $el.css('border-color','');
                    $error.hide();
                }

                console.log(data);
            },
            error:function(){}
        });
    }

});
