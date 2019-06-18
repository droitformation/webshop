$(document).ready(function () {
    $("#wizard").steps({
        bodyTag: "fieldset",
        onStepChanging: function(event, currentIndex, newIndex) {
            // Always allow going backward even if the current step contains invalid fields!
            if (currentIndex > newIndex) {
                return true;
            }

            let form = $(this);

            // Clean up if user went backward before
            if (currentIndex < newIndex) {
                // To remove error styles
                $(".body:eq(" + newIndex + ") label.error", form).remove();
                $(".body:eq(" + newIndex + ") .error", form).removeClass("error");
            }

            // Disable validation on fields that are disabled or hidden.
            form.validate().settings.ignore = ":disabled,:hidden";

            // Start validation; Prevent going forward if false
            return form.valid();
        },
        onStepChanged: function(event, currentIndex, priorIndex) {
            // Suppress (skip) "Warning" step if the user is old enough and wants to the previous step.
            if (currentIndex === 2 && priorIndex === 3) {
                $(this).steps("previous");
                return;
            }

            // Suppress (skip) "Warning" step if the user is old enough.
            if (currentIndex === 2 && Number($("#age").val()) >= 18) {
                $(this).steps("next");
            }
        },
        onFinishing: function(event, currentIndex) {
            let form = $(this);
            // Disable validation on fields that are disabled.
            // At this point it's recommended to do an overall check (mean ignoring only disabled fields)
            form.validate().settings.ignore = ":disabled";
            // Start validation; Prevent form submission if false
            return form.valid();
        },
        onFinished: function(event, currentIndex) {
            let form = $(this);
            // Submit form input
            form.submit();
        }
    }).validate({
        errorPlacement: function(error, element) {
            element.before(error);
        }
    });


    /*
       //Initialize tooltips
       $('.nav-tabs > li a[title]').tooltip();

       //Wizard
       $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {

           let $target = $(e.target);

           if ($target.parent().hasClass('disabled')) {
               returalse;
           }
       });

      /* $(".next-step").click(function (e) {

           let valid = $('#colloque-inscription')[0].checkValidity();

           if(!valid){
               $("#colloque-inscription").find("#submit-hidden").click();
           }
           else{
               let $active = $('.wizard .nav-tabs li.active');
               $active.next().removeClass('disabled');
               nextTab($active);
           }

       });
       $(".prev-step").click(function (e) {

           let $active = $('.wizard .nav-tabs li.active');
           prevTab($active);

       });*/
});

function nextTab(elem) {
    $(elem).next().find('a[data-toggle="tab"]').click();
}
function prevTab(elem) {
    $(elem).prev().find('a[data-toggle="tab"]').click();
}