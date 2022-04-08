(function() {
    'use strict';

    angular.module('mcms.frontEnd.ssg')
        .config(config);

    config.$inject = ['$routeProvider','FRONTEND_CONFIG'];

    function config($routeProvider,Config) {
        $routeProvider
            .when('/front/ssg', {
                templateUrl:  Config.templatesDir + 'Ssg/index.html',
                controller: 'SsgHomeController',
                controllerAs: 'VM',
                reloadOnSearch : false,
                resolve: {
                    Init : ["AuthService", '$q', 'SsgService', function (ACL, $q, SSGS) {
                        return (!ACL.inGates('editableRegions.menu')) ? $q.reject(403) : SSGS.init();
                    }]
                },
                name: 'ssg-home'
            });
    }


})();
