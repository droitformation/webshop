$(document).ready(function () {
    //Initialize tooltips
    $('.nav-tabs > li a[title]').tooltip();

    //Wizard
    $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {

        let $target = $(e.target);

        if ($target.parent().hasClass('disabled')) {
            return false;
        }
    });

    $(".next-step").click(function (e) {

        let $active = $('.wizard .nav-tabs li.active');
        $active.next().removeClass('disabled');

        let formElements = new Array();

        $(this).closest('.tab-pane').find(":input:checked").each(function(){
            formElements.push($(this));
        });

        console.log(formElements);

        nextTab($active);

    });
    $(".prev-step").click(function (e) {

        let $active = $('.wizard .nav-tabs li.active');
        prevTab($active);

    });
});

function nextTab(elem) {
    $(elem).next().find('a[data-toggle="tab"]').click();
}
function prevTab(elem) {
    $(elem).prev().find('a[data-toggle="tab"]').click();
}