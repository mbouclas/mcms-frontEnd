(function() {
    'use strict';

    angular.module('mcms.frontEnd.formBuilder')
        .controller('FormLogController',Controller);

    Controller.$inject = ['Item', 'FormLogService'];

    function Controller(Item, FormLogService) {
        var vm = this;
        vm.Item = Item;

    }

})();
