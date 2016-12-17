(function () {
    'use strict';

    angular.module('mcms.frontEnd.permalinkArchive')
        .service('PermalinkArchiveService',Service);

    Service.$inject = ['lodashFactory', 'PermalinkArchiveDataService', '$q'];

    function Service(lo, DS, $q) {
        var _this = this,
            Items = [];

        this.init = init;
        this.newItem = newItem;
        this.save = save;
        this.destroy = destroy;

        function init() {
            return DS.index().then(function (response) {
                Items = response;
                return Items;
            });
        }

        function newItem() {
            return {
                old_link : '',
                new_link : ''
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
    }
})();
