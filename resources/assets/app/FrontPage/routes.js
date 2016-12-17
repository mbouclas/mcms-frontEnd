(function() {
    'use strict';

    angular.module('mcms.frontEnd.frontPage')
        .config(config);

    config.$inject = ['$routeProvider','FRONTEND_CONFIG'];

    function config($routeProvider,Config) {

        $routeProvider
            .when('/front/frontPage', {
                templateUrl:  Config.templatesDir + 'FrontPage/index.html',
                controller: 'FrontPageHomeController',
                controllerAs: 'VM',
                reloadOnSearch : false,
                resolve: {
                    init : ["AuthService", '$q', 'FrontPageService', function (ACL, $q, FrontPage) {
                        return (!ACL.role('admin')) ? $q.reject(403) : FrontPage.init();
                    }]
                },
                name: 'front-page-home'
            });
    }

})();
