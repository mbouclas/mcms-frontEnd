(function () {
    'use strict';

    angular.module('mcms.frontEnd.formBuilder')
        .service('FormLogService',Service);

    Service.$inject = ['lodashFactory', 'LangService', 'FormLogDataService', '$q', 'core.services',
        '$location', 'FormBuilderService'];

    function Service(lo, Lang, DS, $q, Helpers, $location, FormBuilderService) {
        var _this = this,
            Filters = {},
            Forms = [];


        this.init = init;
        this.get = get;
        this.find = find;
        this.newForm = newForm;
        this.save = save;
        this.destroy = destroy;
        this.availableFilters = availableFilters;
        this.forms = forms;

        function init() {
            var tasks = [
                get(),
                FormBuilderService.get()
            ];

            return $q.all(tasks)
                .then(function (response) {
                    Forms = response[1];
                    return response[0];
                });
        }


        function get(filters) {

            Filters = Helpers.parseLocation(availableFilters(), $location.search());
            if (lo.isObject(filters)){
                Filters = angular.extend(Filters, filters);
            }

            var toFilter = angular.copy(Filters);
            if (typeof toFilter != 'undefined' && (toFilter.field && toFilter.fieldValue)){
                toFilter.data = toFilter.field + '::' + toFilter.fieldValue;
            }

            return DS.index(toFilter)
                .then(function (response) {

                    return response;
                });
        }

        function find(id) {
            return DS.show(id)
                .then(function (item) {
                    return item;
                });

        }

        function newForm() {
            return {
                id : null,
                title : '',
                slug : '',
                provider : [],
                label : Lang.langFields(),
                description : Lang.langFields(),
                fields : [],
                settings : {},
                meta : {},
            };
        }

        function save(item) {
            if (!item.id){
                return DS.store(item);
            }

            return DS.update(item);
        }

        function destroy(item) {
            return DS.destroy(item.id);
        }

        function availableFilters(reset) {
            if (!lo.isEmpty(Filters) && !reset){
                return Filters;
            }

            return {
                id: null,
                field: null,
                form_id: null,
                fieldValue: null,
                dateMode: 'created_at',
                orderBy : 'created_at',
                way : 'DESC',
                page: 1,
                limit :  10
            };
        }

        function forms() {
            return Forms;
        }
    }
})();
