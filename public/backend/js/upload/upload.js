var App = angular.module('upload', ['flow'] , function($interpolateProvider)
{
    $interpolateProvider.startSymbol('{[{').endSymbol('}]}');

}).config(['flowFactoryProvider', function (flowFactoryProvider) {
        /* Flow image upload configuration */
        flowFactoryProvider.defaults = {
            target    : 'admin/upload',
            testChunks:false,
            singleFile: true,
            permanentErrors: [404, 500, 501],
            simultaneousUploads: 4
        };
}]);

/**
 * Form controller, controls the form for creating new content blocs
 */
App.controller("UploadController",['$scope','$http', function($scope,$http){

    $scope.$on('flow::fileError', function (event, $flow, flowFile) {
        event.preventDefault();//prevent file from uploading
        $flow.removeFile(flowFile);
        $('.errorUpload').html('Le fichier est trop volumineux').show();
    });

    $scope.$on('flow::fileAdded', function (event, $flow, flowFile) {
        $('.errorUpload').hide();
    });

    $scope.$on('flow::fileSuccess', function(event, $flow, flowFile, message) {
        var response = JSON.parse(message);
        console.log(response.files);
        $('#'+ response.id).val(response.files);
    });

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
