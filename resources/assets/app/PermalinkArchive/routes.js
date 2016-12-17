(function() {
    'use strict';

    angular.module('mcms.frontEnd.permalinkArchive')
        .config(config);

    config.$inject = ['$routeProvider','FRONTEND_CONFIG'];

    function config($routeProvider,Config) {

        $routeProvider
            .when('/front/permalinkArchive', {
                templateUrl:  Config.templatesDir + 'PermalinkArchive/index.html',
                controller: 'PermalinkArchiveHomeController',
                controllerAs: 'VM',
                reloadOnSearch : false,
                resolve: {
                    Items : ["AuthService", '$q', 'PermalinkArchiveService', function (ACL, $q, PAS) {
                        return (!ACL.inGates('website.permalinkArchive.menu')) ? $q.reject(403) : PAS.init();
                    }]
                },
                name: 'permalink-archive-home'
            });
    }


})();
