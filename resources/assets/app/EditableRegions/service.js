(function () {
    'use strict';

    angular.module('mcms.frontEnd.editableRegions')
        .service('EditableRegionService',Service);

    Service.$inject = ['lodashFactory', 'LangService', 'EditableRegionDataService', '$q', 'ItemSelectorService'];

    function Service(lo, Lang, DS, $q, ItemSelector) {
        var _this = this,
            Regions = [];

        this.init = init;
        this.region = region;
        this.update = update;


        function init() {
            return DS.index().then(function (response) {
                Regions = response.regions;
                ItemSelector.register(response.connectors);
                return Regions;
            });
        }

        function region(id) {

            if (Regions.length == 0) {
                return init()
                    .then(function (r) {
                        return region(id);
                    });
            }

            return $q.resolve(lo.find(Regions, {name : id}));

        }

        function regions() {
            return Regions;
        }

        function update(id, region) {
            return DS.update(id, region);
        }

    }
})();
