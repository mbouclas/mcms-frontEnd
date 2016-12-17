(function () {
    'use strict';

    angular.module('mcms.frontEnd.frontPage')
        .service('FrontPageDataService',Service);

    Service.$inject = ['$http', '$q'];

    function Service($http, $q) {
        var _this = this;
        var baseUrl = '/admin/api/front-page/';



        function returnData(response) {
            return response.data;
        }
    }
})();
