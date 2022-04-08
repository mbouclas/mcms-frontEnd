(function() {
    'use strict';

    angular.module('mcms.frontEnd.ssg')
        .controller('SsgHomeController',Controller);

    Controller.$inject = ['Init', 'SsgService', '$rootScope', '$scope'];

    function Controller(InitialLoad, service, $rootScope, $scope) {
        this.building = false;
        this.buildComplete = false;
        this.buildFailed = false;
        this.buildProgress = false;
        this.currentBuild;
        this.items = InitialLoad;
        this.progressOutput = '';
        // Once the build is complete, refresh the data
        $rootScope.$on('buildCompleted', ($event, data) => {
            service.all()
                .then(items => {
                    this.items = items;
                    this.building = false;
                    this.buildComplete = true;

                    setTimeout(() => {
                        this.buildComplete = false;
                        $scope.$apply();
                    }, 5000);
                });
        });

        $rootScope.$on('buildFailed', ($event, data) => {
            this.buildFailed = true;
            setTimeout(() => {
                this.buildFailed = false;
                $scope.$apply();
            }, 5000);
        });

        $rootScope.$on('buildProgress', ($event, data) => {

        });

        this.build = () => {
            this.building = true;
            service.startBuild()
                .then(res => {
                    this.currentBuild = res;
                    console.log(this.currentBuild)
                    return res;
                })
                .then(() => service.all())
                .then(items => this.items = items)
                .catch(e => console.log(e));
        }
    }

})();
