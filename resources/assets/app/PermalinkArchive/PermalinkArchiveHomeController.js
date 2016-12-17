(function() {
    'use strict';

    angular.module('mcms.frontEnd.permalinkArchive')
        .controller('PermalinkArchiveHomeController',Controller);

    Controller.$inject = ['Items', 'PermalinkArchiveService', 'core.services', 'configuration'];

    function Controller(Items, PAS, Helpers, Config) {
        var vm = this;

        vm.ValidationMessagesTemplate = Config.validationMessages;
        vm.Items = Items;
        vm.newItem = PAS.newItem();

        vm.edit = function (id) {
            vm.editNow = id;
        };

        vm.save = function (item, $index) {
            PAS.save(item)
                .then(function () {
                    vm.editNow = null;
                    Helpers.toast('Saved!');
                });

        };

        vm.add = function () {
            PAS.save(vm.newItem)
                .then(function (items) {
                    vm.newItem = PAS.newItem();
                    vm.Items = items;
                    Helpers.toast('Saved!');
                });
        };


        vm.delete = function (item, $index) {
            Helpers.confirmDialog({}, {})
                .then(function () {
                    PAS.destroy(item).then(function () {
                        vm.Items.splice($index, 1);
                        Helpers.toast('Saved!');
                    });
                });

        };
    }

})();
