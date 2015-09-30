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




