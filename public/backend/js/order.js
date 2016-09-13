/*
 * Select row
 * Product add to new order
 * */

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