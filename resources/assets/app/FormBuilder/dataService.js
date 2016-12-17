(function () {
    'use strict';

    angular.module('mcms.frontEnd.formBuilder')
        .service('FormBuilderDataService',Service);

    Service.$inject = ['$http', '$q'];

    function Service($http, $q) {
        var _this = this;
        var baseUrl = '/admin/api/formBuilder/';

        this.index = index;
        this.store = store;
        this.show = show;
        this.update = update;
        this.destroy = destroy;
        this.schema = schema;
        this.template = template;
        this.providers = providers;

        function index(filters) {
            return $http.get(baseUrl, {params : filters}).then(returnData);
        }

        function store(item) {
            return $http.post(baseUrl, item)
                .then(returnData);
        }

        function show(id) {
            return $http.get(baseUrl + id)
                .then(returnData);
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

        function schema(name) {
            return $http.get(baseUrl + 'schema', {params : {schema : name}}).then(returnData);
        }

        function template() {
            return $http.get(baseUrl + 'template').then(returnData);
        }

        function providers() {
            return $http.get(baseUrl + 'providers').then(returnData);
        }
    }
})();
