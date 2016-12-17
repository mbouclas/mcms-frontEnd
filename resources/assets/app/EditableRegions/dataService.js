(function () {
    'use strict';

    angular.module('mcms.frontEnd.editableRegions')
        .service('EditableRegionDataService',Service);

    Service.$inject = ['$http', '$q'];

    function Service($http, $q) {
        var _this = this;
        var baseUrl = '/admin/api/editableRegions/';

        this.index = index;
        this.update = update;

        function index(filters) {
            return $http.get(baseUrl, {params : filters}).then(returnData);
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
