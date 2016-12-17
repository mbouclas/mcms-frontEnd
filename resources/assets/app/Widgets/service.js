(function () {
    'use strict';

    angular.module('mcms.frontEnd.widgets')
        .service('WelcomeWidgetService',Service);

    Service.$inject = ['lodashFactory', 'LangService', 'WelcomeWidgetDataService', '$q'];

    function Service(lo, Lang, DS, $q) {
        var _this = this,
            WelcomeWidget = [];

        this.get = get;
        this.update = update;


        function get(filters) {
            return DS.index(filters)
                .then(function (response) {
                    WelcomeWidget = response;
                    return response;
                });
        }


        function update(id, item) {
            return DS.update(id, item);
        }

    }
})();
