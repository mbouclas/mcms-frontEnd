(function () {
    'use strict';

    angular.module('mcms.frontEnd.ssg')
        .service('SsgDataService',Service);

    Service.$inject = ['$http', '$q'];

    function Service($http, $q) {
        const _this = this;
        var baseUrl = '/admin/api/ssg/';

        this.index = index;
        this.update = update;

        this.startBuild = () => {
            return $http.post(`${baseUrl}start-build`).then(res => res.data);
        }


        function index(filters) {
            return $http.get(baseUrl, {params : filters}).then(res => res.data);
        }

        function update(id, item) {
            return $http.put(baseUrl + id, item)
                .then(returnData);
        }

        function returnData(response) {
            return response.data;
        }
    }
})();
