(function() {
    'use strict';

    angular.module('mcms.frontEnd.formBuilder')
        .config(config);

    config.$inject = ['$routeProvider','FRONTEND_CONFIG'];

    function config($routeProvider,Config) {

        $routeProvider
            .when('/front/formBuilder', {
                templateUrl:  Config.templatesDir + 'FormBuilder/index.html',
                controller: 'FormBuilderHomeController',
                controllerAs: 'VM',
                reloadOnSearch : false,
                resolve: {
                    Items : ["AuthService", '$q', 'FormBuilderService', function (ACL, $q, FormBuilder) {
                        return (!ACL.level(5)) ? $q.reject(403) : FormBuilder.get();
                    }]
                },
                name: 'form-builder-home'
            })
            .when('/front/formBuilder/:id', {
                templateUrl:  Config.templatesDir + 'FormBuilder/editForm.html',
                controller: 'FormBuilderEditController',
                controllerAs: 'VM',
                reloadOnSearch : false,
                resolve: {
                    Item : ["AuthService", '$q', 'FormBuilderService', '$route', function (ACL, $q, FormBuilder, $route) {
                        return (!ACL.level(5)) ? $q.reject(403) : FormBuilder.init($route.current.params.id);
                    }]
                },
                name: 'form-builder-edit'
            })
            .when('/front/formLog', {
                templateUrl:  Config.templatesDir + 'FormBuilder/log.html',
                controller: 'FormLogHomeController',
                controllerAs: 'VM',
                reloadOnSearch : false,
                resolve: {
                    Items : ["AuthService", '$q', 'FormLogService', function (ACL, $q, FormLog) {
                        return (!ACL.level(5)) ? $q.reject(403) : FormLog.init();
                    }]
                },
                name: 'form-log-home'
            })
            .when('/front/formLog/:id', {
                templateUrl:  Config.templatesDir + 'FormBuilder/viewLog.html',
                controller: 'FormLogController',
                controllerAs: 'VM',
                reloadOnSearch : false,
                resolve: {
                    Item : ["AuthService", '$q', 'FormLogService', '$route', function (ACL, $q, FormLogService, $route) {
                        return (!ACL.level(5)) ? $q.reject(403) : FormLogService.find($route.current.params.id);
                    }]
                },
                name: 'form-log-view'
            });
    }
})();
