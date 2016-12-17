(function() {
    'use strict';

    angular.module('mcms.frontEnd.editableRegions')
        .config(config);

    config.$inject = ['$routeProvider','FRONTEND_CONFIG'];

    function config($routeProvider,Config) {

        $routeProvider
            .when('/front/editableRegions', {
                templateUrl:  Config.templatesDir + 'EditableRegions/index.html',
                controller: 'EditableRegionsHomeController',
                controllerAs: 'VM',
                reloadOnSearch : false,
                resolve: {
                    Regions : ["AuthService", '$q', 'EditableRegionService', function (ACL, $q, ERS) {
                        return (!ACL.inGates('editableRegions.menu')) ? $q.reject(403) : ERS.init();
                    }]
                },
                name: 'editable-regions-home'
            })
            .when('/front/editableRegions/:id', {
                templateUrl:  Config.templatesDir + 'EditableRegions/edit.html',
                controller: 'EditableRegionController',
                controllerAs: 'VM',
                reloadOnSearch : false,
                resolve: {
                    Region : ["AuthService", '$q', 'EditableRegionService', '$route', function (ACL, $q, ERS, $route) {
                        return (!ACL.inGates('editableRegions.edit')) ? $q.reject(403) : ERS.region($route.current.params.id);
                    }]
                },
                name: 'edit-editable-region'
            });
    }


})();
