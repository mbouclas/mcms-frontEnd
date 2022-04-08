(function () {
    'use strict';


    angular.module('mcms.frontEnd.ssg')
        .service('SsgService',Service);

    Service.$inject = ['lodashFactory', 'LangService', 'SsgDataService', '$rootScope'];

    function Service(lo, Lang, DS, $rootScope) {

        const _this = this;
        this.records = [];
        this.init = init;

        this.all = () => {
           return DS.index()
                .then(items => {
                    this.records = items;

                    return items;
                });
        }

        this.startBuild = () => {
            const source = new EventSource("/admin/api/ssg/notifications");
            const eventListener  = (event) => {
                let data = JSON.parse(event.data);
                if (data.state && data.state === 'completed') {
                    $rootScope.$broadcast('buildCompleted', data);
                    source.close();
                }

                if (data.state && data.state === 'failed') {
                    $rootScope.$broadcast('buildFailed', data);
                    source.close();
                }

                if (data.state && data.state === 'progress') {
                    $rootScope.$broadcast('buildProgress', data);
                }
            };

            source.addEventListener('message', eventListener, false);

            return DS.startBuild();
        };

        function init() {

            return _this.all();
        }
    }
})();
