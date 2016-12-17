(function () {
    'use strict';

    angular.module('mcms.frontEnd.frontPage')
        .service('FrontPageService',Service);

    Service.$inject = ['FrontPageDataService', 'LangService', 'lodashFactory', '$q'];

    function Service(DS, Lang, lo, $q) {
        var _this = this;
        this.init = init;


        function init() {

            return $q.resolve([]);
        }

    }
})();
