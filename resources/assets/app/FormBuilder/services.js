(function () {
    'use strict';

    angular.module('mcms.frontEnd.formBuilder')
        .service('FormBuilderService',Service);

    Service.$inject = ['lodashFactory', 'LangService', 'FormBuilderDataService', '$q'];

    function Service(lo, Lang, DS, $q) {
        var _this = this,
            Schema = [],
            Providers,
            Template = {};

        this.init = init;
        this.get = get;
        this.find = find;
        this.newForm = newForm;
        this.save = save;
        this.destroy = destroy;
        this.schema = schema;
        this.template = template;
        this.activeSchema = activeSchema;
        this.providers = providers;
        this.getProviders = getProviders;
        this.getTemplate = getTemplate;
        this.providersFlat = providersFlat;
        this.syncProviderWithMeta = syncProviderWithMeta;

        function init(id) {
            return $q.all([
                (isNaN(parseInt(id))) ? $q.resolve(newForm()) : DS.show(id),
                schema(),
                getProviders(),
                template()
            ])
                .then(function (response) {
                    Schema = response[1];
                    Providers = response[2];
                    Template = response[3];
                    return normalizeItem(response[0]);
                });
        }

        function get(filters) {
            return DS.index(filters)
                .then(function (response) {

                    return response;
                });
        }

        function find(id) {
            return DS.show(id)
                .then(function (item) {
                    if (!lo.isObject(item.settings) || lo.isArray(item.settings)){
                        item.settings = {};
                    }

                    if (!lo.isObject(item.meta) || lo.isArray(item.meta)){
                        item.meta = {};
                    }
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

        function schema(name) {
            return DS.schema(name);
        }

        function template() {
            return DS.template();
        }

        function activeSchema() {
            return Schema;
        }

        function providers() {
            return Providers;
        }

        function getProviders() {
            return DS.providers();
        }

        function getTemplate() {
            return Template;
        }

        function providersFlat() {
            var flat = {};
            for (var i in Providers){
                flat[Providers[i].varName] = Providers[i];
            }

            return flat;
        }

        function normalizeItem(item) {
            if (!lo.isObject(item.meta) || lo.isArray(item.meta)){
                item.meta = {
                    providers : {}
                };
            }

            if (!lo.isObject(item.meta.providers) || lo.isArray(item.meta.providers)){
                item.meta.providers = {};
            }

            if (!lo.isArray(item.provider)){
                item.provider = [];
            }


            return item;
        }

        function syncProviderWithMeta(item) {
            //what we need to do is sync the provider array with the meta object
            for (var i in item.provider){
                var id = item.provider[i];
                //check if it's there already
                if (item.meta.providers[id]){
                    continue;
                }
                //not there, add it
                item.meta.providers[id] = {}
            }
        }
    }
})();
