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
});