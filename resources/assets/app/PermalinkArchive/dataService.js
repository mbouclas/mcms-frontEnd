(function () {
    'use strict';

    angular.module('mcms.frontEnd.permalinkArchive')
        .service('PermalinkArchiveDataService',Service);

    Service.$inject = ['$http', '$q'];

    function Service($http, $q) {
        var _this = this;
        var baseUrl = '/admin/api/permalinkArchive/';

        this.index = index;
        this.update = update;
        this.index = index;
        this.store = store;
        this.show = show;
        this.update = update;
        this.destroy = destroy;

        function index() {
            return $http.get(baseUrl).then(returnData);
        }

        function store(item) {
            return $http.post(baseUrl, item)
                .then(returnData);
        }

        function show(id) {

        }

        function update(item) {
            return $http.put(baseUrl + item.id, item)
                .then(returnData);
        }

        function destroy(id) {
            return $http.delete(baseUrl + id)
                .then(returnData);
        }

        function returnData(response) {
            return response.data;
        }
    }
})();
