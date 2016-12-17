(function() {
    'use strict';

    angular.module('mcms.frontEnd.editableRegions')
        .controller('EditableRegionsHomeController',Controller);

    Controller.$inject = ['Regions'];

    function Controller(Regions) {
        var vm = this;


        vm.Regions = Regions;

    }

})();
