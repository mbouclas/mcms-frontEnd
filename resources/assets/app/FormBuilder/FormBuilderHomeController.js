(function() {
    'use strict';

    angular.module('mcms.frontEnd.formBuilder')
        .controller('FormBuilderHomeController',Controller);

    Controller.$inject = ['Items'];

    function Controller(Items) {
        var vm = this;
        vm.Items = Items;

    }

})();
