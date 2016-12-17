(function() {
    'use strict';

    angular.module('mcms.frontEnd.formBuilder')
        .controller('FormLogHomeController',Controller);

    Controller.$inject = ['Items', 'FormLogService', '$mdBottomSheet', '$mdSidenav', '$rootScope',
        '$location', 'core.services', '$scope'];

    function Controller(Items, FormLogService, $mdBottomSheet, $mdSidenav, $rootScope, $location, Helpers, $scope) {
        var vm = this;
        vm.Items = Items.data;
        vm.Pagination = Items;
        vm.filters = FormLogService.availableFilters();
        vm.Forms = FormLogService.forms();


        Helpers.clearLocation($scope);

        function filter() {
            vm.Loading = true;
            vm.Items = [];

            return FormLogService.get(vm.filters)
                .then(function (res) {
                    $location.search(vm.filters);
                    vm.Loading = false;
                    vm.Pagination = res;
                    vm.Items = res.data;
                    $rootScope.$broadcast('scroll.to.top');
                });
        }

        vm.changePage = function (page, limit) {
            vm.filters.page = page;
            // console.log(vm.filters);
            filter();
        };

        vm.applyFilters = function () {
            vm.filters.page = 1;
            filter();
        };

        vm.listItemClick = function($index) {
            $mdBottomSheet.hide(clickedItem);
        };

        vm.toggleFilters = function () {
            $mdSidenav('filters').toggle();
        };

        vm.resetFilters = function () {
            resetFilters();
            filter();
        };


        function resetFilters() {
            vm.filters = FormLogService.availableFilters(true);
        }
    }

})();
