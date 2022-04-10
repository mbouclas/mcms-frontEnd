(function () {
    'use strict';


    angular.module('mcms.frontEnd.ssg')
        .service('SsgService',Service);

    Service.$inject = ['lodashFactory', 'LangService', 'SsgDataService', '$rootScope'];

    function Service(lo, Lang, DS, $rootScope) {
        this.provider;
        const _this = this;
        this.records = [];
        this.init = init;

        this.setProvider = (provider) => {
            this.provider = provider;
            return this;
        }

        this.getProvider = () => {return this.provider;}

        this.all = () => {
           return DS.index()
                .then(items => {
                    this.records = items;

                    return items;
                });
        }

        this.startBuild = () => {
            return DS.startBuild()
                .then(res => {
                    // Start the SSE with the id returned.
                    // Show a loading icon on the new entry on the build list
                    this.startSSE(res.id);
                    return res;
                });
        };

        this.startSSE = (id) => {
            const source = new EventSource(`/admin/api/ssg/notifications/${id}`);
            const eventListener  = (event) => {
                let data = JSON.parse(event.data);

                // When latest stage === deploy, stop execution

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
        }

        this.deployment = (id) => {
            return DS.getDeployment(id);
        }

        function init() {
            return Promise.all([
                DS.init(),
                this.all(),
            ]).then(res => {
                this.setProvider(res[0].provider);

                return {
                    config: res[0],
                    items: res[1],
                }
            })
        }
    }
})();
