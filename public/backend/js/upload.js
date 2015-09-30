var App = angular.module('upload', ['flow'] , function($interpolateProvider)
{
    $interpolateProvider.startSymbol('{[{').endSymbol('}]}');

}).config(['flowFactoryProvider', function (flowFactoryProvider) {
        /* Flow image upload configuration */
        flowFactoryProvider.defaults = {
            target: 'admin/upload',
            testChunks:false,
            singleFile: true,
            permanentErrors: [404, 500, 501],
            simultaneousUploads: 4
        };
}]);

$( function() {

    Dropzone.autoDiscover = false;
    $("div#progammeUpload").dropzone({ url: "/admin/upload", uploadMultiple : false,
        sending: function(file, xhr, formData) {
            formData.append("_token", $("meta[name='token']").attr('content'));
            formData.append("type", 'programme');
            formData.append("path", 'files/colloques');
        },
        success: function(file, res){
            console.log('upload success...');

        }
    });

    $('#uploadSubmit').on('click', function(e) {
        e.preventDefault();
        //trigger file upload select
        $("#progammeUpload").trigger('click');
    });
});
